<div class='row'>
	<div class='six column'>
		<h3 class='shadow'>Grades for <?=date('M d, Y');?></h3>
		<?foreach ($grades[1] as $grade) {?>
		<div class='row'>
			<div class='twelve column'>
				<h4 class='inline'><?=$grade['name'];?></h4>
				<h4 class='inline right'>Grade&nbsp;-&nbsp;<?=$grade['letter'];?></h4>
			</div>
		</div>
		<?}?>
	</div>
	<div class='six column'>
	</div>
</div>