<?php
/* Copyright (C) 2010-2011	Regis Houssin <regis.houssin@capnetworks.com>
 * Copyright (C) 2013		Juanjo Menent <jmenent@2byte.es>
 * Copyright (C) 2014       Marcos García <marcosgdf@gmail.com>
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
 */

// Protection to avoid direct call of template
if (empty($conf) || ! is_object($conf))
{
	print "Error, template page can't be called as URL";
	exit;
}

?>

<!-- BEGIN PHP TEMPLATE -->

<!--  Xav Modif ICONEO -->
<div id="lastFactCreate"></div>
<!-- Fin  Xav Modif ICONEO -->

<?php

global $user;
global $noMoreLinkedObjectBlockAfter;

$langs = $GLOBALS['langs'];
$linkedObjectBlock = $GLOBALS['linkedObjectBlock'];

$langs->load("bills");

$total=0; $ilink=0;
// Xav Modif ICONEO
$idContract = GETPOST('id','int');
$lastId;
$itIsAContract = strpos($_SERVER['PHP_SELF'], 'contrat');
$k=0;
// fin Xav Modif ICONEO

foreach($linkedObjectBlock as $key => $objectlink)
{
    $ilink++;
    // Xav Modif ICONEO
    if ($k==0) {
        $lastId = $objectlink->id;
        $k=1;
    }
   // echo "ICONEO :  ". $k . " FFF : " .$lastId;
    $socId = $object->socid;
    // fin Xav Modif ICONEO
    
    $trclass='oddeven';
    if ($ilink == count($linkedObjectBlock) && empty($noMoreLinkedObjectBlockAfter) && count($linkedObjectBlock) <= 1) $trclass.=' liste_sub_total';
?>
	<tr class="<?php echo $trclass; ?>" data-element="<?php echo $objectlink->element; ?>"  data-id="<?php echo $objectlink->id; ?>" >
        <td class="linkedcol-element" ><?php echo $langs->trans("CustomerInvoice"); ?></td>
        <td class="linkedcol-name" ><?php echo $objectlink->getNomUrl(1); ?></td>
    	<td class="linkedcol-ref" align="center"><?php echo $objectlink->ref_client; ?></td>
    	<td class="linkedcol-date" align="center"><?php echo dol_print_date($objectlink->date,'day'); ?></td>
    	<td class="linkedcol-amount" align="right"><?php
    		if ($user->rights->facture->lire) {
    			$sign = 1;
    			if ($object->type == Facture::TYPE_CREDIT_NOTE) $sign = -1;
    			if ($objectlink->statut != 3)		// If not abandonned
    			{
    				$total = $total + $sign * $objectlink->total_ht;
    				echo price($objectlink->total_ht);
    			}
    			else
    			{
    				echo '<strike>'.price($objectlink->total_ht).'</strike>';
    			}
    		} ?></td>
    	<td class="linkedcol-statut" align="right"><?php echo $objectlink->getLibStatut(3); ?></td>
    	<td class="linkedcol-action" align="right"><a href="<?php echo $_SERVER["PHP_SELF"].'?id='.$object->id.'&action=dellink&dellinkid='.$key; ?>"><?php echo img_picto($langs->transnoentitiesnoconv("RemoveLink"), 'unlink'); ?></a></td>
    </tr>
<?php
}
// Xav Modif ICONEO
if ($itIsAContract !== false) { ?>
<tr >
    <td colspan="4" align="left">
        <a href="<?php echo DOL_URL_ROOT.'/compta/facture.php?action=createLast&facid='.$lastId .'&socid='.$socId. '&origin=contrat&originid='.$idContract ?>"> --- Créer la facture suivante automatiquement ---</a></td>
</tr>    
<?php }
// Xav Modif ICONEO
if (count($linkedObjectBlock) > 1)
{
    ?>
    <tr class="liste_total <?php echo (empty($noMoreLinkedObjectBlockAfter)?'liste_sub_total':''); ?>">
        <td><?php echo $langs->trans("Total"); ?></td>
        <td></td>
    	<td align="center"></td>
    	<td align="center"></td>
    	<td align="right"><?php echo price($total); ?></td>
    	<td align="right"></td>
    	<td align="right"></td>
    </tr>
    <?php
}
?>
<!--  Xav Modif ICONEO -->
<script type="text/javascript">
<!--
	document.getElementById('lastFactCreate').innerHTML = '<a href="<?php echo DOL_URL_ROOT.'/compta/facture.php?action=createLast&facid='.$lastId .'&socid='.$socId. '&origin=contrat&originid='.$idContract ?>"> --- Créer la facture suivante automatiquement ---</a>';
//-->
</script>
<!--  Xav Modif ICONEO -->
<!-- END PHP TEMPLATE -->
