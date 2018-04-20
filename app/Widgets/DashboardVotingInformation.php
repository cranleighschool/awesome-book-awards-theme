<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 20/04/2018
 * Time: 08:56
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\Widgets;

use WP_Query;
class DashboardVotingInformation  {

    public $year = 2018;
    private $post_type = "school";
    private $scores = [];
    private $book_ids = [];
    private $num_completed_scoring = 0;
    private $num_schools = 0;
    private $still_to_vote = [];

    function __construct() {

        add_action('wp_dashboard_setup', array($this, 'setup_dashboard'));
    }
    public function setup_dashboard() {
        wp_add_dashboard_widget(
            'awesome_voting_information', // Widget Slug.
            'Voting Information <span style="color:red;">[NEW]</span>', // Title
            array($this, 'widget')
        );
    }

    public static function run() {
        return new self;
    }

    function setUpBooks() {
        $args = [
            "post_type" => 'book',
            "posts_per_page" => -1,
            "tax_query" => array(
                array(
                    'taxonomy' => 'awesome-year',
                    'field' => 'slug',
                    'terms' => $this->year,
                )
            )
        ];
        $ids = array();
        $query = new WP_Query($args);
        while ($query->have_posts()): $query->the_post();
            $ids[] = get_the_ID();
        endwhile;

        $this->book_ids = $ids;

    }
    function sortScores($flag) {
        array_multisort(array_map(function($element) {
            return $element['score'];
        }, $this->scores), $flag, $this->scores);
    }

    function countdownToCloseVoting() {
        $now = new \DateTime("now", new \DateTimeZone("Europe/London"));
        $date = new \DateTime("2018-04-25 23:59:59", new \DateTimeZone("Europe/London"));

        $diff = $date->diff($now);

        $hours = $diff->h;
        $hours = $hours + ($diff->days*24);
        return $hours." hours";
    }

    function widget() {
        $this->getScoresFromSchools();
        self::writeFact('The Year is <strong>'.$this->year.'</strong>');
        self::writeFact("<strong>".$this->calculateNumChildren()."</strong> children have voted this year.");
        self::writeFact("<strong>".$this->num_completed_scoring."</strong> out of <strong>".$this->num_schools." schools</strong> have voted.");
        self::writeFact("There are still <strong>".($this->num_schools - $this->num_completed_scoring)." schools</strong> that we want to vote. <em>(These are listed below)</em>");
        self::writeFact("There are just <strong>".$this->countdownToCloseVoting()." left before voting ends!");
        $this->sortScores(SORT_DESC);
        $this->displayScoresTable();
        $this->displayStillToVoteList();
    }

    private function displayStillToVoteList() {
        echo "<div class=\"still-to-vote\">";
        echo "<h3>Those Schools Still To Vote</h3>";
        echo "<div style='height: 250px;overflow-y: scroll;'>";
        echo "<table class=\"widefat\" style=''>";
        echo "<tr>";
        $i = 0;
        foreach ($this->still_to_vote as $school) {
            if ($i%2) {
                echo "</tr><tr>";
            }
            echo "<td style=\"\">";
            echo $school->post_title."";
            echo "</td>";
            $i++;
        }
        echo "</tr>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
    }
    private function displayScoresTable() {
        ob_start()
        ?>
        <table style="border-collapse: collapse;" class="widefat">
            <thead style="font-weight:bolder; font-style: oblique">
                <th>Book</th>
                <th style="width: 40px">Total Score</th>
            </thead>
            <tbody>
                <?php foreach ($this->scores as $book) {
                    echo "<tr>";
                    echo "<td>".$book['book_title']."</td>";
                    echo "<td>".$book['score']."</td>";
                    echo "</tr>";
                } ?>
            </tbody>
        </table>
        <?php
        $contents = ob_get_contents();
        ob_end_clean();
        echo $contents;
    }

    private function setupBookScoreArray() {
        $this->setUpBooks();
        foreach ($this->book_ids as $book_id) {
            $this->getBook($book_id);
        }
    }

    private function getBook($book_id) {
        $book = get_post($book_id);
        if (get_post_type($book) != 'book') {
            echo 'ERROR';
            return false;
        }

        $this->scores["book_id_$book_id"] = array(
            "score" => null,
            "book_title" => $book->post_title
            //"post" => $book
        );
        return $book;
    }

    private function getScoresFromSchools() {
        $this->setupBookScoreArray();
        $args = [
            "post_type" => $this->post_type,
            "posts_per_page" => -1,
            "orderby" => 'post_title',
            "order" => "asc",
            "tax_query" => array(
                array(
                    'taxonomy' => 'awesome-year',
                    'field' => 'slug',
                    'terms' => $this->year,
                )
            )
        ];

        $query = new WP_Query($args);
        $this->num_schools = $query->post_count;

        // About to loop through 65 schools
        while($query->have_posts()): $query->the_post();

            // Inside is school loop, loop through each book.
            foreach ($this->book_ids as $book_id) {
                $completed_score = false;
                $score = get_post_meta(get_the_ID(), "book_id_$book_id", true);

                if (empty($score)) {
                    break;
                } else {
                    $completed_score = 1;
                }
                $this->appendScore($book_id, $score);
            }

            if ($completed_score !== FALSE) {
                $this->num_completed_scoring++;
            } else {
                $this->still_to_vote[] = get_post(get_the_ID());
            }
        endwhile;
            wp_reset_postdata();
            wp_reset_query();
    }

    public function appendScore($book_id, $score) {
        $current_score = (int) $this->scores["book_id_$book_id"]['score'];
        $this->scores["book_id_$book_id"]['score'] = (int) $score + $current_score;
    }
    public function countScores($book_id) {

    }
    public function calculateNumChildren() {
        $totalvotes = array_sum(array_column($this->scores, 'score'));
        return $totalvotes / 15;
    }
    public static function writeFact($fact) {
        echo '<p>'.$fact.'</p>';
    }

}