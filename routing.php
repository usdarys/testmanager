<?php

use core\App;
use core\Utils;

App::getRouter()->setDefaultRoute('testRunList'); #default action
App::getRouter()->setLoginRoute('noAccess'); #action to forward if no permissions

Utils::addRoute('login', 'LoginCtrl');
Utils::addRoute('logout', 'LoginCtrl');
Utils::addRoute('noAccess', 'LoginCtrl');
Utils::addRoute('userList', 'UserCtrl', ['Admin']);
Utils::addRoute('userSave', 'UserCtrl', ['Admin']);
Utils::addRoute('userUpdate', 'UserCtrl', ['Admin']);
Utils::addRoute('userDelete', 'UserCtrl', ['Admin']);
Utils::addRoute('testCaseList', 'TestCaseCtrl', ['Admin', 'Test Leader']);
Utils::addRoute('testCaseSave', 'TestCaseCtrl', ['Admin', 'Test Leader']);
Utils::addRoute('testRunList', 'TestRunCtrl', ['Admin', 'Test Leader', 'Tester']);
Utils::addRoute('testRunSave', 'TestRunCtrl', ['Admin', 'Test Leader']);
Utils::addRoute('testResultList', 'TestResultCtrl', ['Admin', 'Test Leader', 'Tester']);
Utils::addRoute('testResultSave', 'TestResultCtrl', ['Admin', 'Test Leader', 'Tester']);
Utils::addRoute('testResultUpdate', 'TestResultCtrl', ['Admin', 'Test Leader', 'Tester']);