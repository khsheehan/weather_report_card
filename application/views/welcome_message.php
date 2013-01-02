<div class='row'>
	<div class='eight column'>
		<h3 class='shadow'>Grades for <?=date("M")." ".((date("d"))-1).', '.date("Y");?></h3>
		<?foreach ($grades[1] as $grade) {?>
		<div class='report'>
			<div class='row'>
				<div class='twelve column'>
					<div source='<?=$grade["source_id"];?>' class='source_wrap'>
						<h4 class='inline'><?=$grade['name'];?></h4>
						<div class='grade_wrap'>
							<span class='letter_wrap'>
								<?if($grade['letter']):?>
									<?=$grade['letter'];?>
								<?endif;?>
							</span>
							<span class='number_wrap'>
								<?if($grade['num']):?>
									<?=round($grade['total']/$grade['num']);?>%
								<?endif;?>
							</span>
						</div>
					</div>
					<div class='details details_<?=$grade["source_id"];?>'>
						<table class='report_card'>
							<tr class='head_row'>
								<td>Location</td>
								<td class='center'>Grade</td>
								<td class='center'>Pred Lo</td>
								<td class='center'>Pred Hi</td>
								<td class='center'>Precip. %</td>
								<td class='center'>Lo</td>
								<td class='center'>Hi</td>
								<td class='right'>Precip.</td>
							</tr>
						<?foreach ($grades[0][$grade['source_id']] as $grade) {?>
							<tr>
								<td><?=$grade['name'];?></td>
								<td class='center'><?=$grade['grade'];?>%</td>
								<td class='center'><?=$grade['pred_lo'];?>&#176;</td>
								<td class='center'><?=$grade['pred_hi'];?>&#176;</td>
								<td class='center'><?=$grade['pred_pop'];?>%</td>
								<td class='center'><?=$grade['real_lo'];?>&#176;</td>
								<td class='center'><?=$grade['real_hi'];?>&#176;</td>
								<?if($grade['real_pop']):?>
								<td class='right'>Yes</td>
								<?else:?>
								<td class='right'>No</td>
								<?endif;?>
							</tr>
						<?}?>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?}?>
	</div>
	<div class='four column'>
		<h3 class='shadow'>(Semi) Local Grades</h3>
		<form method='get' action='<?=site_url('zip');?>'>
			<input name='zip' class='zip_input' placeholder='zipcode' type='text' />
		</form>
	</div>
</div>