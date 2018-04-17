<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 02/02/2018
 * Time: 10:57
 */

namespace CranleighSchool\AwesomeBookAwardsTheme\Shortcodes;


class VoteForm extends BaseShortcode {

	protected $tag = 'vote-form';
	protected $atts = [];

	public function render($atts, $content=null) {
		$this->atts = shortcode_atts([], $atts);
		$this->pageURI = get_permalink();

		if (isset($_POST['vote_confirmation']) && $_POST['vote_confirmation']=="on") {
			$update = $this->submitScores();

			return "Updated";

		} elseif (isset($_POST['email']) && $_POST['email']!= "") {
			return $this->displayVoteForm();
		} else {
			return $this->setUpVoteForm();
		}

	}
	private function submitDateStamp($post_id) {

		$dates = get_post_meta($post_id, 'vote_submitted', true);
		if (!is_array($dates)) {
			$dates = array();
		}
		array_push($dates, date('Y-m-d H:i:s'));
		update_post_meta($post_id, 'vote_submitted', $dates);

	}

	private function submitScores() {
		$school_id = $_POST['school_id'];
		foreach ($_POST as $key => $data) {
			if (substr($key, 0, 8)=="book_id_") {
				update_post_meta($school_id, $key, $data);
			}
		}

		$this->submitDateStamp($school_id);

		return true;
	}

	private function setUpVoteForm() {
		ob_start();
		?>
		<form method="post">
			<div class="form-group">
				<label for="email">Your Email Address</label>
				<input placeholder="Your Email Address" required="required" type="email" name="email" class="form-control" id="email" />
			</div>
			<input type="submit" class="btn btn-lg btn-success" />
		</form>
		<?php
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	private function formBookRow($book, $school) {
		ob_start();
		$score = get_post_meta($school->ID, "book_id_".$book->ID, true); ?>

			<label class="sr-only" for="book_id_<?php echo $book->ID; ?>"><?php echo $book->post_title; ?></label>
			<input type="number" required="required" value="<?php echo $score; ?>" name="book_id_<?php echo $book->ID; ?>" class="form-control score-count" style="text-align: center;" />


		<?php
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	private function findSchoolFromContact($contact) {
		global $wpdb;

		$post_id = $wpdb->get_var( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key='school_contact' AND meta_value='{$contact}'");
		if ($post_id === NULL) {
			return new \WP_Error("Record Not Found");
		}
		return $post_id;

	}

	private function displayVoteForm() {
		$votingSchoolID = $this->findSchoolFromContact($_POST['email']);
		$school = get_post($votingSchoolID);
		if (is_wp_error($votingSchoolID)) {
			return "<h2>Error</h2>"
				."<p class='text-danger'>We could not find your school in our database. <a href='mailto:ekkr@cranprep.org'>Please contact Emma Reid for assistance</a>.</p>"
				."<a href='".$this->pageURI."'><button type='button' class='btn btn-info'>Try Again...</button></a>";
		}


		$books = new \WP_Query([
			"posts_per_page" => -1,
			"post_type" => 'book',
			"tax_query" => [
				[
					"taxonomy" => "awesome-year",
					"field" => "slug",
					"terms" => date("Y")
				]
			]
		]);
		$vBooks = $books->posts;
		ob_start();?>

		<form method="post">
			<h3>About You</h3>
			<div class="form-group">
				<label for="school">School</label>
				<input type="text" name="school" class="form-control" id="school" disabled="disabled" value="<?php echo $school->post_title; ?>" />
			</div>

			<div class="form-group">
				<label for="email">Your Email Address</label>
				<input type="email" name="email" class="form-control" id="email" disabled="disabled" value="<?php echo get_post_meta($school->ID, 'school_contact', true); ?>" />
			</div>
			<h3>Your School's Votes</h3>
			<input type="hidden" name="school_id" value="<?php echo $school->ID; ?>" />
            <p class="text-danger"><small>Please note: you can do this once. Only children who have read all 5 books are eligible to have their votes cast. Please complete this form, before voting closes, when your pupils have read all 5 books.</small></p>
            <table style="margin-bottom:10px;">
				<thead>
				<?php foreach ($vBooks as $book):

					echo "<th class='text-center'>".get_the_post_thumbnail($book->ID, 'thumb', ['style' => 'margin-bottom:5px;'])."</th>";


		endforeach; ?>
				</thead>
				<tbody>
					<tr class="form-group">

			<?php
			foreach ($vBooks as $book):
				echo "<td class='text-center' id='book-score-inputs'>";
				echo $this->formBookRow(get_post($book->ID), $school);
				echo "</td>";
			endforeach;
			?></tr>
				</tbody>
			</table>

            <div class="form-group">
				<button type="button" id="check-calculations" class="btn btn-large btn-warning">Check Calculations</button>
			</div>
			<div class="form-group" style="display: none;" id="input-total-children-group">
				<label for="total_children">Total Number of Children Voting For</label>
				<input type="number" name="total_children" class="form-control" id="total_children" />
			</div>
			<div id="vote_results_check"></div>

			<div class="form-group show-after-confirmation" style="display: none;">
				<label for="vote_confirmation"><input type="checkbox" required="required" id="vote_confirmation" name="vote_confirmation" style="" /> If you agree with the data shown on this page, please tick the box to confirm.</label>
			</div>


			<input type="submit" style="display:none;" value="Vote" class="show-after-confirmation btn btn-large btn-outline-success" />
		</form>
		<script type="text/javascript">
			jQuery.wait = function( callback, seconds){
				return window.setTimeout( callback, seconds * 1000 );
			};

			jQuery(document).ready(function() {
				jQuery("input[type=number]").keyup(function() {
					jQuery(".show-after-confirmation").slideUp();
					jQuery("#vote_results_check").hide();
				});

				jQuery( "#check-calculations" ).click(function(e) {
					e.preventDefault();
					jQuery("#vote_results_check").show();
					jQuery("input:checkbox#vote_confirmation").removeAttr("checked");

					jQuery("#vote_results_check").html('<i class="fa fa-fw fa-spin fa-spinner"></i>');

					var totalPoints = 0;

					jQuery('.score-count').each(function(){
						totalPoints = totalPoints + parseInt(jQuery(this).val());
					});

					jQuery.wait(function() {
						if (totalPoints % 15 === 0) {
							children = totalPoints / 15;
							jQuery("#vote_results_check").html('<p class="text-success">Your maths adds up, suggesting that you have had <strong><u>'+children+' pupils</u></strong> finish all 5 books and vote.</p>');
							jQuery("#input-total-children-group input").val(children);
							jQuery(".show-after-confirmation").slideDown();
						} else {
							jQuery("#vote_results_check").html('<p class="text-danger">There\'s a problem with your scoring. Something doesn\'t add up. Please double check and try &quot;Check Calculations&quot; again. If the problem persists please contact us.</p><p><small><strong>Reasoning:</strong> The total sum of scores should always be divisible by 15.</small></p>');
						}
					}, 1)
				});


			});
		</script>
		<?php
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	private function displayBooksByYear() {
		$year = $this->atts['year'];
		$books = new Book();
		$posts = $books->getPosts([
			"tax_query" => [
				[
					"taxonomy" => 'awesome-year',
					'field' => 'slug',
					'terms' => $year
				]
			]
		]);
		$taxonomy = get_term_by('slug', $year, 'awesome-year');

		ob_start();

		echo '<div class="row books-shortlist">';
		if ($this->atts['notitle']===false):
			echo '<div class="col-md-12">';
			echo '<h3>'.sprintf($this->atts['title'], $taxonomy->name).'</h3>';
			echo '</div>';
		endif;
		echo '<div class="col-md-1"></div>';

		while($posts->have_posts()): $posts->the_post();
			echo '<div class="col-md-2 col-6">';
			echo "<h4 class='sr-only'>".get_the_title()."</h4>";
			echo '<a href="'.get_permalink().'">';
			the_post_thumbnail('book-cover', ["style" => "margin-bottom:10px;"]);
			echo '</a>';
			echo '</div>';
		endwhile;
		echo '<div class="col-md-1"></div></div>';

		wp_reset_postdata();
		wp_reset_query();

		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}
}
