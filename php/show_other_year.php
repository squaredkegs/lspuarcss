
<select name='other_year' style='display:block;' class='form-control'>
<option value=''>Select Year</option>
					<?php
						$curr_year = date("Y");
						$limit_year = $curr_year - 6;
						for($x = $curr_year;$x>=$limit_year;$x--){
					?>
						<option value='<?php echo $x;?>'><?php echo $x;?></option>
					<?php
						}
					?>
</select>