<link rel='stylesheet' href='./codemirror.css'/>
<link rel='stylesheet' href='./bootstrap.min.css' />
<script type='text/javascript' src='./jquery.js'></script>
<script type='text/javascript' src='http://localhost/editor/workspace/wordpress/wp-admin/js/code-editor.min.js?ver=5.1.1'></script>
<script type='text/javascript' src='http://localhost/editor/workspace/wordpress/wp-includes/js/wp-util.min.js?ver=5.1.1'></script>
<script type='text/javascript' src='http://localhost/editor/workspace/wordpress/wp-admin/js/theme-plugin-editor.min.js?ver=5.1.1'></script>
<link rel='stylesheet' href='./screen.css'/>
<script type='text/javascript'>
jQuery( function( $ ) { wp.themePluginEditor.init(
	$( "#newcontent" ), {"codeEditor":{"codemirror":{"indentUnit":4,"indentWithTabs":true,"inputStyle":"contenteditable","lineNumbers":true,"lineWrapping":true,"styleActiveLine":true,"continueComments":true,"extraKeys":{"Ctrl-Space":"autocomplete","Ctrl-\/":"toggleComment","Cmd-\/":"toggleComment","Alt-F":"findPersistent","Ctrl-F":"findPersistent","Cmd-F":"findPersistent"},"direction":"ltr","gutters":[],"mode":"php","autoCloseBrackets":true,"autoCloseTags":true,"matchBrackets":true,"matchTags":{"bothTags":true}},"csslint":{"errors":true,"box-model":true,"display-property-grouping":true,"duplicate-properties":true,"known-properties":true,"outline-none":true},"jshint":{"boss":true,"curly":true,"eqeqeq":true,"eqnull":true,"es3":true,"expr":true,"immed":true,"noarg":true,"nonbsp":true,"onevar":true,"quotmark":"single","trailing":true,"undef":true,"unused":true,"browser":true,"globals":{"_":false,"Backbone":false,"jQuery":false,"JSON":false,"wp":false}},"htmlhint":{"tagname-lowercase":true,"attr-lowercase":true,"attr-value-double-quotes":false,"doctype-first":false,"tag-pair":true,"spec-char-escape":true,"id-unique":true,"src-not-empty":true,"attr-no-duplication":true,"alt-require":true,"space-tab-mixed-disabled":"tab","attr-unsafe-chars":true}}} 
	); } )
</script>
<style>
.CodeMirror{
	height:100% !important;
}	
</style>


<?php
 

$form_submit = '';
if(isset($_POST['change_dir'])){
	
		if(file_exists($_POST['filename'])){
			
		
		$current_dir = $_POST['filename'];	
		$scan_dir = $current_dir;
		if(is_dir($current_dir)){
				 
		}else{
			$file = explode('/',$current_dir);
			$scan_dir  = str_ireplace($file[count($file) - 1],'',$current_dir);
		}
		
		}else{
			
		}
		
		$form_value = $_POST['filename'];

}else{
	$form_value = '';
	$current_dir = '/';
	$scan_dir = '/';
} 
	
 
if(isset($_POST['save_file'])){
			$file = explode('/',$_POST['path']);
			$save_dir = str_ireplace($file[count($file) - 1],'',$_POST['path']);	
          
            $write = fopen('./backup/' . $file[count($file) - 1].'_'.time(), 'w') or die("can't open file ".$save_dir . $file[count($file) - 1]);
            fwrite($write, $_POST['oldfile']);
            fclose($write);	
            
            $write = fopen($save_dir . $file[count($file) - 1], 'w') or die("can't open file ".$save_dir . $file[count($file) - 1]);
            fwrite($write, $_POST['newcontent']);
            fclose($write);	            
            
            $current_dir = $_POST['path'];
            $scan_dir = $save_dir;
            $form_submit = 'done';
}
 
 
if(isset($_GET['reset_backup']) == 1){
error_reporting(0);	
$dirs = './backup/';
$allFiles = scandir($dirs);
          foreach ($allFiles as $fname){
             unlink($dirs.''.$fname);
          }	
} 

?>


 


 

 	<h1 style='text-align:center;'>Basic Editor</h1>
	<div class='row'>
		<div class='container'>
			<form action='' method='POST'>
				<input type='text' id='filepath' name='filename' class='form-control' value='<?php if($form_value != ''){ echo $form_value; }else{ echo $current_dir; } ?>' autofocus='yes'>
				<div class='row'>
					<div class='col-md-10'>
						<input type='submit' name='change_dir' value='Submit' class='btn btn-primary form-control'>	
					</div>
					<div class='col-md-2'>
						<a target='_blank' href='?reset_backup=1' class='btn btn-primary form-control'>reset backup</a>
					</div>
				</div>
			</form>
		</div>
	</div>
 
<div class='row'>
	<div class='col-md-12'>
		<?php 
			if(is_dir($current_dir)){
		?>
				<ul class='list-group'>
							<?php
							$dirs = $scan_dir;
							
							$tmp = array();
							$allFiles = scandir($dirs);
							          foreach ($allFiles as $fname){
							              if($fname == '.' || $fname == '..' ){
							                  continue;
							              }
							              if(is_dir($dirs.'/'.$fname)){
							                  $tmp[] = $fname;
							              }
							         
							            	echo '<a href="#"  file="'.$fname.'" onclick="item_click(this);" ><li class="list-group-item">'.$fname.'</li></a>';	
							              
							              
							          } 
							 
							//print_r($tmp);
							?>
							</ul>		
		<?php
			}else{  
		?>
		<div id="root-editor-wrapper" style="height: 214px;"><div class="editor ace_editor ace_dark ace-twilight" draggable="false" style="font-size: 13px;">
		<form action='' method='POST'>
		<input type="submit" name="save_file" id='save_file'  class="btn btn-primary" value="Update File"  style='position:fixed; z-index:99; left:-1px; bottom:1px;'>
		<input type='hidden' name='path' value='<?php echo $current_dir; ?>'>
		<?php 
			if($form_submit != ''){
				?>
				<div class='alert-success'>file saved! and backup!</div>
				<?php
			}
		?>
		<textarea  name="newcontent" id="newcontent"><?php  if(isset($current_dir)){	if(is_dir($current_dir)){  }else{ echo file_get_contents($current_dir); } }?></textarea>
		<textarea  name="oldfile" style="display:none"><?php  if(isset($current_dir)){	if(is_dir($current_dir)){  }else{ echo file_get_contents($current_dir); } } ?></textarea>
		</form>
		</div>
		<?php 
			}
		?>
	</div>
</div> 
 

<script>
	function item_click(e){
		document.getElementById('filepath').value += '/'+e.getAttribute('file');
	}
</script>
	 

		
		