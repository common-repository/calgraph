<?php
/*
Plugin Name: CALgraph
Description: Graphing by date (calendar) with lines, asterisk, cross or triangle. Requires php-image-graph, php-image-canvas, php-gd, php-pear, Image_Color. Calgraph grew out of Simple Graph by Pasi Matilainen. 
Author: Ray Holland 
Version: 0.9.1 
Author URI: http://abacms.org/
*/ 

define('CALGRAPH_PLUGIN_PATH', ABSPATH . '/wp-content/plugins/' .
	dirname(plugin_basename(__FILE__)));

define('CALGRAPH_PLUGIN_URL', get_bloginfo('wpurl') . '/wp-content/plugins/'
	. dirname(plugin_basename(__FILE__)));

$calgraph_version		= "0.9";
$calgraph_db_verison		= "0.9";

error_reporting(1);

function draw_calgraph($table_prefix, $current_user,$table_id,$table_name){
include_once 'Image/Graph.php';     
global $wpdb;
$Graph =& Image_Graph::factory('graph', array(800,600)); 
$Graph->add(
    Image_Graph::vertical(
        Image_Graph::factory('title', array("$table_name", 12)),        
        Image_Graph::vertical(
            $Plotarea = Image_Graph::factory('plotarea'),
            $Legend = Image_Graph::factory('legend'),
            90
        ),
       10 
    )
); 

$Legend->setPlotarea($Plotarea);
$variables_yaxis=$wpdb->get_col("SELECT DISTINCT variabley FROM ".$table_prefix."calgraph WHERE user_id={$current_user->data->ID} and table_id=$table_id ORDER by variabley ASC");
$typeys=$wpdb->get_col("SELECT DISTINCT typey FROM ".$table_prefix."calgraph WHERE user_id={$current_user->data->ID} and table_id=$table_id ORDER by variabley ASC");

$num_variables_yaxis=sizeof($variables_yaxis);
  for($i=0;$i<=$num_variables_yaxis-1;$i+=1){
$datings[$i]=$wpdb->get_results("SELECT stamp,value FROM ".$table_prefix."calgraph WHERE user_id={$current_user->data->ID} and variabley='$variables_yaxis[$i]' and table_id=$table_id ORDER by variabley ASC, stamp ASC");


$linecount[$i]=$wpdb->get_var("SELECT count(typey) FROM ".$table_prefix."calgraph WHERE user_id={$current_user->data->ID} and variabley='$variables_yaxis[$i]' and typey REGEXP 'Line'");

$Dataset[$i] =& Image_Graph::factory('dataset'); 

if  ($linecount[$i] > 1){ 
if ($typeys[$i] == "Line1"){
$linestyle =& Image_Graph::factory('Image_Graph_Line_Solid','blue');
$linestyle->setThickness(3); 
$Plot[$i] =& $Plotarea->addNew('line', array(&$Dataset[$i]));
$Plot[$i]->setLineStyle($linestyle);
$Plot[$i]->setTitle($variables_yaxis[$i]); 
}
else if ($typeys[$i] == "Line2"){
$linestyle =& Image_Graph::factory('Image_Graph_Line_Solid','red');
$linestyle->setThickness(3); 
$Plot[$i] =& $Plotarea->addNew('line', array(&$Dataset[$i]));
$Plot[$i]->setLineStyle($linestyle);
$Plot[$i]->setTitle($variables_yaxis[$i]); 
}
else if ($typeys[$i] == "Line3"){
$linestyle =& Image_Graph::factory('Image_Graph_Line_Solid','fuchsia');
$linestyle->setThickness(3); 
$Plot[$i] =& $Plotarea->addNew('line', array(&$Dataset[$i]));
$Plot[$i]->setLineStyle($linestyle);
$Plot[$i]->setTitle($variables_yaxis[$i]); 
}
else if ($typeys[$i] == "Line4"){
$linestyle =& Image_Graph::factory('Image_Graph_Line_Solid','lawngreen');
$linestyle->setThickness(3); 
$Plot[$i] =& $Plotarea->addNew('line', array(&$Dataset[$i]));
$Plot[$i]->setLineStyle($linestyle);
$Plot[$i]->setTitle($variables_yaxis[$i]); 
}
else if ($typeys[$i] == "Line5"){
$linestyle =& Image_Graph::factory('Image_Graph_Line_Solid','yellow');
$linestyle->setThickness(3); 
$Plot[$i] =& $Plotarea->addNew('line', array(&$Dataset[$i]));
$Plot[$i]->setLineStyle($linestyle);
$Plot[$i]->setTitle($variables_yaxis[$i]); 
}
else if ($typeys[$i] == "Horizontal Line"){
$Plot[$i] =& $Plotarea->addNew('line', array(&$Dataset[$i]));
$Plot[$i]->setLineColor('brown');
$Plot[$i]->setTitle($variables_yaxis[$i]); 
}
}
if ($typeys[$i] == "Triangle"){
$Plot[$i] =& $Plotarea->addNew('Image_Graph_Plot_Dot', array(&$Dataset[$i]));
$Marker1 =& Image_Graph::factory('Image_Graph_Marker_Triangle');
$Marker1->setFillColor('green');
$Marker1->setLineColor('black');
$Plot[$i]->setMarker($Marker1);
$Plot[$i]->setTitle($variables_yaxis[$i]); 
}
else if ($typeys[$i] == "Cross"){
$Plot[$i] =& $Plotarea->addNew('Image_Graph_Plot_Dot', array(&$Dataset[$i]));
$Marker2 =& Image_Graph::factory('Image_Graph_Marker_Plus');
$Marker2->setFillColor('yellow');
$Marker2->setLineColor('black');
$Plot[$i]->setMarker($Marker2);
$Plot[$i]->setTitle($variables_yaxis[$i]); 
}
else if ($typeys[$i] == "Vertical Line"){
$Plot[$i] =& $Plotarea->addNew('Plot_Impulse', array(&$Dataset[$i]));
$Plot[$i]->setLineColor('gold');
$Plot[$i]->setTitle($variables_yaxis[$i]);
}
else if ($typeys[$i] == "Asterisk"){
$Plot[$i] =& $Plotarea->addNew('Image_Graph_Plot_Dot', array(&$Dataset[$i]));
$Marker2 =& Image_Graph::factory('Image_Graph_Marker_Asterisk');
$Marker2->setFillColor('cyan');
$Marker2->setSize(6);
$Marker2->setLineColor('cyan');
$Plot[$i]->setMarker($Marker2);
$Plot[$i]->setTitle($variables_yaxis[$i]); 
}
       foreach ($datings[$i] as $daty){
		$Dataset[$i]->addPoint($daty->stamp, $daty->value);
}
}

$Font =& $Graph->addNew('ttf_font', '/usr/share/fonts/truetype/freefont/FreeMonoBold');
$Font->setSize(12);
$Graph->setFont($Font);

$Plotarea->Image_Graph_Plotarea('Image_Graph_Axis','Image_Graph_Axis');
$Plotarea->setAxisPadding(5, 'bottom');
$Plotarea->setAxisPadding(25, 'left');
$Plotarea->setAxisPadding(25, 'right');
$Plotarea->setAxisPadding(25, 'top');
$YAxis =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
$XAxis =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
$XAxis->setTitle('Date');
include_once 'Image/Graph/DataPreprocessor/Date.php';
$dateFormatter = new Image_Graph_DataPreprocessor_Date("m/d");
$XAxis->setDataPreProcessor(&$dateFormatter);
$Graph->done( 
array('filename' => '../wp-content/uploads/'.$current_user->user_login.''.$table_id.'_calgraph.png')
);
}

function get_table_name_calgraph($table_id) {
global $wpdb,$current_user;
$table_name = $wpdb->get_var("SELECT DISTINCT table_name FROM {$wpdb->prefix}calgraph WHERE user_id={$current_user->data->ID} and table_id=$table_id;");
return $table_name;
}

function calgraph_install() {
	

	global $wpdb;
	if (!current_user_can('activate_plugins')) return;
	$table_name1 = $wpdb->prefix . 'calgraph';
	$table_name2 = $wpdb->prefix . 'variables_yaxis';

	// if variables_yaxis table doesn't exist, create it 
	if ( $wpdb->get_var("show tables like '$table_name2'") != $table_name2) {
	$sql= "CREATE TABLE $table_name2 (
  	ID bigint(20) PRIMARY KEY AUTO_INCREMENT,
  	variabley varchar(250) NOT NULL,
	UNIQUE KEY variabley (variabley))";
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
	dbDelta($sql);
	}

	// if calgraph table doesn't exist, create it 
	if ( $wpdb->get_var("show tables like '$table_name1'") != $table_name1 ) {
		$sql = "CREATE TABLE $table_name1 (
			id int PRIMARY KEY AUTO_INCREMENT,
			variabley varchar(250) NOT NULL,
			typey varchar(250) NOT NULL,
                        user_id bigint(20) NOT NULL,
                        table_id int NOT NULL,
                        table_name varchar(250) NOT NULL,
			stamp int NOT NULL,
			value double NOT NULL)";
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql);
	}
}

register_activation_hook(__FILE__,'calgraph_install');

function cal_managePanel() {
	if (function_exists('add_management_page')) {
		add_management_page('CALgraph','CALgraph',7,basename(__FILE__),'cal_show_manage_panel');
	}
}

add_action('admin_menu','cal_managePanel');

function cal_show_manage_panel() {
global $wpdb, $current_user, $wp_version;
$table_prefix = $wpdb->prefix;
if (isset($_GET['calgraph_delete'])) { 
$item_id = $_GET['calgraph_delete'];
$table_id=$wpdb->get_var("SELECT table_id FROM ".$table_prefix."calgraph WHERE id=".$item_id." AND user_id={$current_user->data->ID}");
$sql = "DELETE FROM ".$table_prefix."calgraph WHERE id=".$item_id." AND user_id=".$current_user->data->ID;
$wpdb->query($sql);
}


if (isset($_POST['calgraph_value'])) {
// insert data here
$date = strtotime($_POST['calgraph_year']."-".$_POST['calgraph_month']."-".$_POST['calgraph_day']);
$value = $wpdb->escape($_POST['calgraph_value']);
$table_id = array_shift(split(':',$wpdb->escape($_POST['calgraph_table_id'])));
$table_name = trim(array_pop(split(':',$wpdb->escape($_POST['calgraph_table_id']))));
$variabley = $wpdb->escape($_POST['calgraph_variabley']);
$typey = $wpdb->escape($_POST['calgraph_typey']);
$sql = "INSERT INTO ".$table_prefix."calgraph (variabley, typey, user_id, table_id, table_name, stamp, value) values ('$variabley','$typey',{$current_user->data->ID},$table_id,'$table_name',$date,$value)";
if (!empty($value)){
$wpdb->query($sql);
}
$table_name=get_table_name_calgraph($table_id);
if (empty($table_name)) {
?>
<div class="error"><p><strong><?php _e('Create New? Please Enter a Value.');
?></strong></p></div><?php
} else {
draw_calgraph($table_prefix, $current_user,$table_id,$table_name);
} 
}
if (isset($_POST['graph_table_name'])) {
$graph_table_id = $wpdb->escape($_POST['graph_table_id']);
$graph_table_name = $wpdb->escape($_POST['graph_table_name']);
$sql = "UPDATE ".$table_prefix."calgraph set table_name='$graph_table_name' WHERE user_id={$current_user->data->ID} and table_id=$graph_table_id";
$wpdb->query($sql);
}
?>

<div class="wrap">
<h2>Add/Delete Variable</h2>
<form method="post">
<fieldset class="options">
<table class="editform optiontable">

        <tr>
                <th scope="row"><?php _e('Add') ?>:</th>
                <td><input name="variabley" type="text" /></td>
                <?php $variabley=trim($wpdb->escape($_POST['variabley'])); 
if (!empty($variabley)) {
$wpdb->query("INSERT INTO {$wpdb->prefix}variables_yaxis (variabley) VALUES ('$variabley')"); 
}
?>
</tr>
<?php
$variabley = $wpdb->escape($_POST['calgraph_variabley_delete']);
if (!empty($_POST['calgraph_variabley_delete'])) {
$wpdb->query("DELETE FROM {$wpdb->prefix}variables_yaxis where variabley=('$variabley')"); 
}
?>
<tr>
<th scope="row"><?php _e('Delete'); ?>:</th>
<td><select name="calgraph_variabley_delete">
<option value="" selected="selected">None</option> 
<?php
$variables_yaxis= $wpdb->get_col("SELECT variabley FROM {$wpdb->prefix}variables_yaxis ORDER BY variabley");
foreach ($variables_yaxis as $variabley) 
{
echo "<option value='$variabley'>$variabley</option>";
}

?>
</select>
</td>
</tr>
</table>
</fieldset>
<p align="right">
        <input name="addvariabley" type="submit" value="<?php _e('Add/Delete Variable &raquo;') ?>" />
</p>
</form>
</div>

<div class="wrap">
<h2>Update/Display Graph</h2>
<form method="post">
<fieldset class="options">
<table class="editform optiontable">
<tr>
<th scope="row"><?php _e('Graph'); ?>:</th>
<td><select name="calgraph_table_id"><?php
$tables = $wpdb->get_results("SELECT table_id,table_name FROM {$wpdb->prefix}calgraph WHERE user_id={$current_user->data->ID} group BY table_name ASC;");
$high_table = 0;
foreach ($tables as $table) {
	$sel = '';
	if (isset($_POST['calgraph_table_id']))
		if ($table->table_id == $_POST['calgraph_table_id'])
			$sel = ' selected="selected"';
	echo '<option value="'.$table->table_id.': '.$table->table_name.'"'.$sel.'>'.$table->table_id.': '.$table->table_name.'</option>';
	if ($table->table_id>$high_table)
		$high_table = $table->table_id;
}
$high_table++;
echo '<option value="'.$high_table.'">'.$high_table.': (Create new)</option>';
?></select>
</td>
</tr>

<tr>
<th scope="row"><?php _e('Variable'); ?>:</th>
<?php
$variables_yaxis= $wpdb->get_col("SELECT variabley FROM {$wpdb->prefix}variables_yaxis ORDER BY variabley");
if (empty($variables_yaxis)){
?>
<td>
<font color=#ff0000><strong>None [Add Variable Above to Start]</strong></font>
</td>
<?php 
}
else {
?>
<td><select name="calgraph_variabley">
<?php
foreach ($variables_yaxis as $variabley) 
{
echo "<option value='$variabley'>$variabley</option>";
}
?>
</select>
</td>
<?php
}
?>
</tr>

<tr>
<th scope="row"><?php _e('Type'); ?>:</th>
<td><select name="calgraph_typey">
<option value="Line1">Line1</option> 
<option value="Line2">Line2</option> 
<option value="Line3">Line3</option> 
<option value="Line4">Line4</option> 
<option value="Line5">Line5</option> 
<option value="Asterisk">Asterisk</option> 
<option value="Cross" >Cross</option> 
<option value="Triangle">Triangle</option> 
<option value="Horizontal Line">Horizontal Line</option> 
<option value="Vertical Line">Vertical Line</option> 
</select>
</td>
</tr>

<tr>
<th scope="row"><?php _e('Date'); ?>:</th>
<td>
<select name="calgraph_month"><?php
for ($m = 1; $m<=12; $m++) { ?>
<option value="<?php printf("%02d",$m); ?>"<?php if ($m==date("m")) echo 
" selected=\"selected\""; ?>><?php printf("%02d",$m); ?></option>
<?php } ?></select>
<select name="calgraph_day"><?php
for ($m = 1; $m<=31; $m++) { ?>
<option value="<?php printf("%02d",$m); ?>"<?php if ($m==date("d")) echo 
" selected=\"selected\""; ?>><?php printf("%02d",$m); ?> &nbsp; </option>
<?php } ?></select>
<select name="calgraph_year"><?php
$year = date("Y")-2;
for ($y = $year; $y<$year+5; $y++) { ?>
<option value="<?php echo $y; ?>"<?php if ($y==($year+2)) echo " selected=\"selected\"";?>><?php echo $y; ?></option>
<?php } ?></select>
</td></tr>
<tr>
<th scope="row"><?php _e('Value'); ?>:</th>
<td><input type="text" name="calgraph_value" /></td>
</tr>
</table>
</fieldset>
<p align="right">
<input type="submit" name="graph_insert" value="<?php _e('Update/Display Graph'); ?> &raquo;" />
</p>
</form>
</div>

<?php
if (!empty($tables)) {
?>

<div class="wrap">
<h2>Set Title</h2>
<form method="post">
<fieldset class="options">
<table class="editform optiontable">
<tr>
<th scope="row"><?php _e('Graph#'); ?>:</th>
<td><select name="graph_table_id"><?php
$tables = $wpdb->get_results("SELECT DISTINCT(table_id) FROM {$wpdb->prefix}calgraph WHERE user_id={$current_user->data->ID} ORDER BY table_id ASC;");
$high_table = 0;
foreach ($tables as $table) {
        $sel = '';
        if (isset($_POST['graph_table_id']))
                if ($table->table_id == $_POST['graph_table_id'])
                        $sel = ' selected="selected"';
        echo '<option value="'.$table->table_id.'"'.$sel.'>'.$table->table_id.'</option>';
        if ($table->table_id>$high_table)
                $high_table = $table->table_id;
}
?></select>
</td>
</tr>

<tr>
<th scope="row"><?php _e(' Title'); ?>:</th>
<td><select name="graph_table_name">
<?php
$variables_yaxis= $wpdb->get_col("SELECT variabley FROM {$wpdb->prefix}variables_yaxis ORDER BY variabley");
foreach ($variables_yaxis as $variabley)
{
echo "<option value='$variabley'>$variabley</option>";
}
?>
</select>
</td>
</tr>
</table>
<p align="right">
<input type="submit" name="addtablename" value="<?php _e('Set Title'); ?> &raquo;" />
</p>
</form>
</div>

<?php
}

if (!empty($table_id)) {
$table_name=get_table_name_calgraph($table_id);
}
if (!empty($table_name)) { ?>
<div class="wrap">
<table id="the-list-x" width="100%" cellpadding="3" cellspacing="3">
<tr><th align="left">Graph#</th><th align="left">Variable</th><th align="left">Type</th><th align="left">Date</th><th align="left">Value</th></tr>
<?php
$sql = "SELECT * FROM ".$table_prefix."calgraph WHERE user_id={$current_user->data->ID} and table_id=$table_id ORDER BY variabley DESC, stamp DESC";
$valueset = $wpdb->get_results($sql); 
	foreach ($valueset as $values) { 
		$class = ('alternate' == $class) ? '' : 'alternate'; ?>
<tr id="post-"<?php echo $values->id; ?>" class="<?php echo $class; ?>">
<td><?php echo $values->table_id; ?></td>
<td><?php echo $values->variabley; ?></td>
<td><?php echo $values->typey; ?></td>
<td><?php echo date("m.d.Y",$values->stamp); ?></td>
<td><?php echo $values->value; ?></td>
<?php
if ($wp_version == "2.7") {
?>
<td><a href="tools.php?page=calgraph.php&amp;calgraph_delete=<?php echo $values->id; ?>"" onClick="return confirm('Delete Value?')""><?php _e(' Delete'); ?></a></td>
<?php
}
else {
?>
<td><a href="edit.php?page=calgraph.php&amp;calgraph_delete=<?php echo $values->id; ?>"" onClick="return confirm('Delete Value?')""><?php _e(' Delete'); ?></a></td>
<?php
}
?>
</tr>	
<?php 
}
draw_calgraph($table_prefix, $current_user,$table_id,$table_name);
?>
</table>
<?php $browser = $_SERVER['HTTP_USER_AGENT'];
$rowcount = count($valueset);
if ($rowcount < 2) {
?>
<div class="wrap"><p><?php _e('Graph will appear after another value is entered');
?></p></div><?php
}
if ($rowcount > 1) {
$table_name=get_table_name_calgraph($table_id);
if (is_numeric($table_name)) {
?>
<div class="error"><p><strong><?php echo "Graph Title is '$table_name'. Please Use Set Title Above";
?></strong></p></div><?php
}
if (eregi('iPhone', $browser)) { ?>
<center><img width="100%" src="<?php echo '../wp-content/uploads/'.$current_user->user_login.''.$table_id.'_calgraph.png';?>"></center>
<?php
}
else {
?>
<center><img src="<?php echo '../wp-content/uploads/'.$current_user->user_login.''.$table_id.'_calgraph.png';?>"></center>
<?php }
}
}
?>
</div>
<?php
}
?>
