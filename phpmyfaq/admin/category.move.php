<?php
/**
 * $Id: category.move.php,v 1.15 2007-03-22 17:51:57 thorstenr Exp $
 *
 * Select a category to move
 *
 * @author      Thorsten Rinne <thorsten@phpmyfaq.de>
 * @since       2004-04-29
 * @copyright   (c) 2004-2007 phpMyFAQ Team
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 */

if (!defined('IS_VALID_PHPMYFAQ_ADMIN')) {
    header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

if ($permission["editcateg"]) {
    $id = (int)$_GET['cat'];
    $parent_id = (int)$_GET['parent_id'];
    $category = new PMF_Category($LANGCODE, $current_admin_user, $current_admin_groups, false);
    $categories = $category->getAllCategories();
    $category->categories = null;
    unset($category->categories);
    $category->getCategories($parent_id, false);
    $category->buildTree($parent_id);
    
    $header = sprintf('%s: <em>%s</em>',
        $PMF_LANG['ad_categ_move'],
        $category->categoryName[$id]['name']);

    printf('<h2>%s</h2>', $header);
?>
	<form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="action" value="changecategory" />
    <fieldset>
        <legend><?php print $PMF_LANG["ad_categ_change"]; ?></legend>
	    <input type="hidden" name="cat" value="<?php print $id; ?>" />
	    <div class="row">
               <select name="change" size="1">
<?php
                    foreach ($category->catTree as $cat) {
                       if ($id != $cat["id"]) {
                          printf("<option value=\"%s\">%s%s</option>", $cat["id"], $indent, $cat["name"]);
                       }
                   }
?>
               </select>&nbsp;&nbsp;
               <input class="submit" type="submit" name="submit" value="<?php print $PMF_LANG["ad_categ_updatecateg"]; ?>" />
            </div>
    </fieldset>
    </form>

<?php
    printf('<p>%s</p>', $PMF_LANG['ad_categ_remark_move']);
} else {
    print $PMF_LANG["err_NotAuth"];
}