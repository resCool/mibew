<?php
/*
 * This file is part of Mibew Messenger project.
 * 
 * Copyright (c) 2005-2011 Mibew Messenger Community
 * All rights reserved. The contents of this file are subject to the terms of
 * the Eclipse Public License v1.0 which accompanies this distribution, and
 * is available at http://www.eclipse.org/legal/epl-v10.html
 * 
 * Alternatively, the contents of this file may be used under the terms of
 * the GNU General Public License Version 2 or later (the "GPL"), in which case
 * the provisions of the GPL are applicable instead of those above. If you wish
 * to allow use of your version of this file only under the terms of the GPL, and
 * not to allow others to use your version of this file under the terms of the
 * EPL, indicate your decision by deleting the provisions above and replace them
 * with the notice and other provisions required by the GPL.
 * 
 * Contributors:
 *    Evgeny Gryaznov - initial API and implementation
 */

require_once('../libs/common.php');
require_once('../libs/operator.php');

$operator = check_login();
force_password($operator);


if (isset($_GET['act'])) {

	$operatorid = isset($_GET['id']) ? $_GET['id'] : "";
	if (!preg_match("/^\d+$/", $operatorid)) {
		$errors[] = getlocal("no_such_operator");
	}

	if ($_GET['act'] == 'del') {
		if (!is_capable($can_administrate, $operator)) {
			$errors[] = "You are not allowed to remove operators";
		}

		if ($operatorid == $operator['operatorid']) {
			$errors[] = "Cannot remove self";
		}

		if (count($errors) == 0) {
			$op = operator_by_id($operatorid);
			if (!$op) {
				$errors[] = getlocal("no_such_operator");
			} else if ($op['vclogin'] == 'admin') {
				$errors[] = 'Cannot remove operator "admin"';
			}
		}

		if (count($errors) == 0) {
			$link = connect();
			perform_query("delete from ${mysqlprefix}chatgroupoperator where operatorid = $operatorid", $link);
			perform_query("delete from ${mysqlprefix}chatoperator where operatorid = $operatorid", $link);
			close_connection($link);

			header("Location: $webimroot/operator/operators.php");
			exit;
		}
	}
	if ($_GET['act'] == 'disable' || $_GET['act'] == 'enable') {
		$act_disable = ($_GET['act'] == 'disable');
		if (!is_capable($can_administrate, $operator)) {
			$errors[] = $act_disable?getlocal('page_agents.disable.not.allowed'):getlocal('page_agents.enable.not.allowed');
		}

		if ($operatorid == $operator['operatorid'] && $act_disable) {
			$errors[] = getlocal('page_agents.cannot.disable.self');
		}

		if (count($errors) == 0) {
			$op = operator_by_id($operatorid);
			if (!$op) {
				$errors[] = getlocal("no_such_operator");
			} else if ($op['vclogin'] == 'admin' && $act_disable) {
				$errors[] = getlocal('page_agents.cannot.disable.admin');
			}
		}

		if (count($errors) == 0) {
			$link = connect();
			perform_query("update ${mysqlprefix}chatoperator set idisabled = ".($act_disable?'1':'0')." where operatorid = $operatorid", $link);
			close_connection($link);

			header("Location: $webimroot/operator/operators.php");
			exit;
		}
	}
}

$page = array();
$page['allowedAgents'] = operator_get_all();
$page['canmodify'] = is_capable($can_administrate, $operator);

setlocale(LC_TIME, getstring("time.locale"));

prepare_menu($operator);
start_html_output();
require('../view/agents.php');
?>
