<?php
/* Copyright (C) 2010-2012 Regis Houssin       <regis.houssin@capnetworks.com>
 * Copyright (C) 2010-2016 Laurent Destailleur <eldy@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * Javascript code to activate drag and drop on lines
 * You can use this if you want to be abale to drag and drop rows of a table.
 * You must add id="tablelines" on table level tag and have ($nboflines or count($object->lines) or count($taskarray) > 0)
 */

// Protection to avoid direct call of template
if (empty($object) || ! is_object($object))
{
	print "Error, template page can't be called as URL";
	exit;
}

?>

<!-- BEGIN PHP TEMPLATE AJAXROW.TPL.PHP - Script to enable drag and drop on tables -->
<?php
$id=$object->id;
$fk_element=$object->fk_element;
$table_element_line=(empty($table_element_line)?$object->table_element_line:$table_element_line);
$nboflines=(isset($object->lines)?count($object->lines):(isset($tasksarray)?count($tasksarray):(empty($nboflines)?0:$nboflines)));
$forcereloadpage=empty($conf->global->MAIN_FORCE_RELOAD_PAGE)?0:1;
$tagidfortablednd=(empty($tagidfortablednd)?'tablelines':$tagidfortablednd);
$filepath=(empty($filepath)?'':$filepath);

if (GETPOST('action','aZ09') != 'editline' && $nboflines > 1) { ?>
<script type="text/javascript">
$(document).ready(function(){
	$(".imgupforline").hide();
	$(".imgdownforline").hide();
    $(".lineupdown").removeAttr('href');
    $(".tdlineupdown").css("background-image",'url(<?php echo DOL_URL_ROOT.'/theme/'.$conf->theme.'/img/grip.png'; ?>)');
    $(".tdlineupdown").css("background-repeat","no-repeat");
    $(".tdlineupdown").css("background-position","center center");

    console.log("Prepare tableDnd for #<?php echo $tagidfortablednd; ?>");
    $("#<?php echo $tagidfortablednd; ?>").tableDnD({
		onDrop: function(table, row) {
			var reloadpage = "<?php echo $forcereloadpage; ?>";
			console.log("tableDND onDrop");
			console.log(decodeURI($("#<?php echo $tagidfortablednd; ?>").tableDnDSerialize()));
			var roworder = cleanSerialize(decodeURI($("#<?php echo $tagidfortablednd; ?>").tableDnDSerialize()));
			var table_element_line = "<?php echo $table_element_line; ?>";
			var fk_element = "<?php echo $fk_element; ?>";
			var element_id = "<?php echo $id; ?>";
			var filepath = "<?php echo urlencode($filepath); ?>";
			$.post("<?php echo DOL_URL_ROOT; ?>/core/ajax/row.php",
					{
						roworder: roworder,
						table_element_line: table_element_line,
						fk_element: fk_element,
						element_id: element_id,
						filepath: filepath
					},
					function() {
						console.log("tableDND end of ajax call");
						if (reloadpage == 1) {
							//console.log('<?php echo dol_escape_js($_SERVER['QUERY_STRING']); ?>');
							location.href = '<?php echo dol_escape_js($_SERVER['PHP_SELF']).'?'.dol_escape_js($_SERVER['QUERY_STRING']); ?>';
						} else {
							$("#<?php echo $tagidfortablednd; ?> .drag").each(
									function( intIndex ) {
										// $(this).removeClass("pair impair");
										//if (intIndex % 2 == 0) $(this).addClass('impair');
										//if (intIndex % 2 == 1) $(this).addClass('pair');
									});
						}
					});
		},
		onDragClass: "dragClass",
		dragHandle: "td.tdlineupdown"
	});
    $(".tdlineupdown").hover( function() { $(this).addClass('showDragHandle'); },
    	function() { $(this).removeClass('showDragHandle'); }
    );
});
</script>
<?php } else { ?>
<script>
$(document).ready(function(){
	$(".imgupforline").hide();
	$(".imgdownforline").hide();
    $(".lineupdown").removeAttr('href');
});
</script>
<?php } ?>
<!-- END PHP TEMPLATE AJAXROW.TPL.PHP -->