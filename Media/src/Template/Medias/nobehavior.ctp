<div class="bloc">
	<div class="content">
		<h1>Erreur</h1>
		<p>Le model <?php echo $ref; ?> n'a pas de comportement Media.</p>

		<pre>
class <?php echo $ref; ?> extends AppModel{

	public $actsAs = array('Media.Media');

}
		</pre>
	</div>
</div>