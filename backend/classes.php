<?php
/**
 * Created by PhpStorm.
 * User: colen
 * Date: 2017/1/22
 * Time: 14:53
 */

require(dirname(__DIR__). '/common/config/bootstrap.php');

require_once(dirname(__DIR__). '/common/services/RabbitMqService.php');

require(dirname(__DIR__). '/common/models/Base.php');
require(dirname(__DIR__). '/common/models/HandicapTrait.php');
require(dirname(__DIR__). '/common/models/Handicap.php');
require(dirname(__DIR__). '/common/models/User.php');

require(dirname(__DIR__). '/backend/controllers/bets/BetController.php');
require(dirname(__DIR__). '/common/models/AgentBetRule.php');
require(dirname(__DIR__). '/common/models/Bet.php');

