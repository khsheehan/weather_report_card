<div class='row'>
	<div class='eight column'>
		<h3 class='shadow'>Grades for <?=$location;?> on <?=date('M d, Y',strtotime($date));?></h3>
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
		<div class='row sidebar_sec'>
			<div class='twelve column'>
				<h3 class='shadow'>(Semi) Local Grades</h3>
				<form method='get' action='<?=site_url('zip');?>'>
					<input name='zip' class='zip_input' placeholder='Zipcode' type='text' />
					<label>Or choose the closest city</label>
					<select name='city' class='eight column'>
						<?foreach ($locations as $location) {?>
						<option value='<?=$location['zip'];?>'><?=$location['name'];?></option>
						<?}?>
					</select>
					<input type='submit' class='four column' value='Submit'/>
				</form>
			</div>
		</div>
		<div class='row sidebar_sec'>
			<div class='twelve column'>
				<h3 class='shadow'>Archived Scores</h3>
				<form method='get' action='<?=site_url('zip');?>'>
					<input name='date' id='datepicker' class='zip_input' placeholder='Choose a date' type='text' />
					<input type='hidden' name='city' value='<?=$zip;?>' />
					<input type='submit' class='four column' value='Submit'/>
				</form>
			</div>
		</div>
	</div>
</div>