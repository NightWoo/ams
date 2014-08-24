angular.module('app-templates', ['_common/modal/modal.tpl.html', 'about/about.tpl.html', 'about/leftNav.tpl.html', 'about/main.tpl.html', 'about/rightNav.tpl.html', 'common/head.master.tpl.html', 'common/home.leftNav.tpl.html', 'demo/demo.form.tpl.html', 'demo/demo.http.tpl.html', 'demo/demo.inputText.tpl.html', 'demo/demo.tpl.html', 'demo/leftNav.tpl.html', 'home/home.tpl.html', 'http/http.tpl.html', 'leftNav/testLeftNav.tpl.html']);

angular.module("_common/modal/modal.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("_common/modal/modal.tpl.html",
    "<div class=\"modal-header\">\n" +
    "    <h3>{{title}}</h3>\n" +
    "</div>\n" +
    "<div class=\"modal-body\">\n" +
    "    {{content}}\n" +
    "</div>\n" +
    "<div class=\"modal-footer\">\n" +
    "    <button class=\"btn btn-primary\" ng-click=\"ok()\">OK</button>\n" +
    "    <button class=\"btn btn-warning\" ng-click=\"cancel()\">Cancel</button>\n" +
    "</div>");
}]);

angular.module("about/about.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("about/about.tpl.html",
    "i'am about\n" +
    "<div ui-view=\"left\"></div>\n" +
    "<div ui-view=\"main\"></div>\n" +
    "<div ui-view=\"right\"></div>");
}]);

angular.module("about/leftNav.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("about/leftNav.tpl.html",
    "<h1>i'm left nav</h1>");
}]);

angular.module("about/main.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("about/main.tpl.html",
    "<h1>i'm main</h1>");
}]);

angular.module("about/rightNav.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("about/rightNav.tpl.html",
    "<h1>i'm right nav</h1>");
}]);

angular.module("common/head.master.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("common/head.master.tpl.html",
    "<div class=\"container\">\n" +
    "    <div class=\"navbar-header\">\n" +
    "      <button class=\"navbar-toggle\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navMaster\">\n" +
    "        <span class=\"sr-only\"></span>\n" +
    "        <span class=\"icon-bar\"></span>\n" +
    "        <span class=\"icon-bar\"></span>\n" +
    "        <span class=\"icon-bar\"></span>\n" +
    "      </button>\n" +
    "      <a href=\"/bms/site\" class=\"navbar-brand\">AMS</a>\n" +
    "    </div>\n" +
    "    <nav class=\"collapse navbar-collapse\" role=\"navigation\" id=\"navMaster\">\n" +
    "      <ul class=\"nav navbar-nav\">\n" +
    "        <li id=\"headManagementSystemLi\">\n" +
    "            <a class=\"visible-sm\" href=\"/bms/ManagementSystem/home?chapter=0\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"体系\"><i class=\"fa fa-sitemap\"></i></a>\n" +
    "            <a class=\"hidden-sm\" href=\"/bms/ManagementSystem/home?chapter=0\"><i class=\"fa fa-sitemap\"></i>&nbsp;体系</a>\n" +
    "        </li>\n" +
    "        <li id=\"headTechnologyLi\">\n" +
    "            <a class=\"visible-sm\" href=\"\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"技术\"><i class=\"fa fa-cogs\"></i></a>\n" +
    "            <a class=\"hidden-sm\" href=\"\"><i class=\"fa fa-cogs\"></i>&nbsp;技术</a>\n" +
    "        </li>\n" +
    "        <li id=\"headAssemblyLi\">\n" +
    "            <a class=\"visible-sm\" href=\"/bms/execution\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"生产\"><i class=\"fa fa-wrench\"></i></a>\n" +
    "            <a class=\"hidden-sm\" href=\"/bms/execution\"><i class=\"fa fa-wrench\"></i>&nbsp;生产</a>\n" +
    "        </li>\n" +
    "        <li class=\"divider-vertical\"></li>\n" +
    "        <li id=\"headEfficiencyLi\">\n" +
    "            <a class=\"visible-sm\" href=\"/bms/site/pannelIndex?pannel=efficiencyPannel\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"效率\"><i class=\"fa fa-dashboard\"></i>&nbsp;</a>\n" +
    "            <a class=\"hidden-sm\" href=\"/bms/site/pannelIndex?pannel=efficiencyPannel\"><i class=\"fa fa-dashboard\"></i>&nbsp;效率</a>\n" +
    "        </li>\n" +
    "        <li id=\"headQualityLi\">\n" +
    "            <a class=\"visible-sm\" href=\"/bms/execution/query?type=NodeQuery\" rel=\"tooltip\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"质量\"><i class=\"fa fa-thumbs-up\"></i>&nbsp;</a>\n" +
    "            <a class=\"hidden-sm\" href=\"/bms/execution/query?type=NodeQuery\"><i class=\"fa fa-thumbs-up\"></i>&nbsp;质量</a>\n" +
    "        </li>\n" +
    "        <li>\n" +
    "            <a class=\"visible-sm\" href=\"#\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"现场\"><i class=\"fa fa-map-marker\"></i>&nbsp;</a>\n" +
    "            <a class=\"hidden-sm\" href=\"#\"><i class=\"fa fa-map-marker\"></i>&nbsp;现场</a>\n" +
    "        </li>\n" +
    "        <li id=\"headCostLi\">\n" +
    "            <a class=\"visible-sm\" href=\"/bms/site/pannelIndex?pannel=costPannel\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"成本\"><i class=\"fa fa-money\"></i>&nbsp;</a>\n" +
    "            <a class=\"hidden-sm\" href=\"/bms/site/pannelIndex?pannel=costPannel\"><i class=\"fa fa-money\"></i>&nbsp;成本</a>\n" +
    "        </li>\n" +
    "        <li id=\"headManpowerLi\">\n" +
    "            <a class=\"visible-sm\" href=\"/bms/humanResources\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"人事\"><i class=\"fa fa-group\"></i>&nbsp;</a>\n" +
    "            <a class=\"hidden-sm\" href=\"/bms/humanResources\"><i class=\"fa fa-group\"></i>&nbsp;人事</a>\n" +
    "        </li>\n" +
    "        <li class=\"divider-vertical\"></li>\n" +
    "        <li id=\"headMonitoringLi\">\n" +
    "            <a class=\"hidden-xs hidden-lg\" href=\"/bms/execution/monitoringIndex\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"监控\"><i class=\"fa fa-desktop\"></i>&nbsp;</a>\n" +
    "            <a class=\"visible-xs visible-lg\" href=\"/bms/execution/monitoringIndex\"><i class=\"fa fa-desktop\"></i>&nbsp;监控</a>\n" +
    "        </li>\n" +
    "        <li id=\"headGeneralInformationLi\">\n" +
    "            <a class=\"hidden-xs hidden-lg\" href=\"/bms/generalInformation\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"数据\"><i class=\"fa fa-list-alt\"></i>&nbsp;</a>\n" +
    "            <a class=\"visible-xs visible-lg\" href=\"/bms/generalInformation\"><i class=\"fa fa-list-alt\"></i>&nbsp;数据</a>\n" +
    "        </li>\n" +
    "      </ul>\n" +
    "      <ul class=\"nav navbar-nav navbar-right\">\n" +
    "        <li>\n" +
    "            <a class=\"hidden-xs\" href=\"/bms/generalInformation/accountMaintain\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"账户管理\"><i class=\"fa fa-user\"></i>&nbsp;<?php echo Yii::app()->user->display_name;?></a>\n" +
    "            <a class=\"visible-xs\" href=\"/bms/generalInformation/accountMaintain\"><i class=\"fa fa-user\"></i>&nbsp;账户管理[<?php echo Yii::app()->user->display_name;?>]</a>\n" +
    "        </li>\n" +
    "        <li>\n" +
    "            <a class=\"hidden-xs\" href=\"/bms/site/logout\" rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"注销\"><i class=\"fa fa-sign-out\"></i>&nbsp;</a>\n" +
    "            <a class=\"visible-xs\" href=\"/bms/site/logout\"><i class=\"fa fa-sign-out\"></i>&nbsp;注销</a>\n" +
    "        </li>\n" +
    "      </ul>\n" +
    "    </nav>\n" +
    "</div>");
}]);

angular.module("common/home.leftNav.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("common/home.leftNav.tpl.html",
    "<div class=\"well\">\n" +
    "    <ul class=\"nav nav-list\">\n" +
    "        <li class=\"nav-header\">home nav</li>\n" +
    "        <li class=\"\"><a href=\"#\">nothing here yet</a></li>\n" +
    "    </ul>\n" +
    "</div>");
}]);

angular.module("demo/demo.form.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("demo/demo.form.tpl.html",
    "<div>\n" +
    "    <div ng-form=\"simple\" class=\"form-horizontal\">\n" +
    "        <fieldset>\n" +
    "            <legend>\n" +
    "                简单表单元素\n" +
    "            </legend>\n" +
    "            <div class=\"row\">\n" +
    "                <div class=\"span6\">\n" +
    "                    <div class=\"control-group\">\n" +
    "                        <label class=\"control-label\">input-text</label>\n" +
    "                        <div class=\"controls\">\n" +
    "                            <input type=\"text\" required ng-model=\"simpleForm.inputText\" id=\"inputText\" placeholder=\"input some text here...\">\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "\n" +
    "                    <div class=\"control-group\">\n" +
    "                        <label class=\"control-label\">textarea</label>\n" +
    "                        <div class=\"controls\">\n" +
    "                            <textarea rows=\"6\" ng-model=\"simpleForm.textarea\" required yt-minlength=\"4\" yt-maxlength=\"10\" popover=\"长度限制4-10个字符\" popover-trigger=\"focus\" popover-append-to-body=\"true\" popover-placement=\"right\"></textarea>\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "\n" +
    "                    <div class=\"control-group\">\n" +
    "                        <label class=\"control-label\">checkbox</label>\n" +
    "                        <div class=\"controls\">\n" +
    "                            <label class=\"checkbox inline\" ng-repeat=\"color in colors\">\n" +
    "                              <input type=\"checkbox\" checklist-model=\"simpleForm.inputCheckbox\" checklist-value=\"color\"><span>{{color}}</span>\n" +
    "                            </label>\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "\n" +
    "                    <div class=\"control-group\">\n" +
    "                        <label class=\"control-label\">redio</label>\n" +
    "                        <div class=\"controls\">\n" +
    "                            <label class=\"redio\" ng-repeat=\"gen in genders\">\n" +
    "                                <input type=\"radio\" value=\"{{gen}}\" ng-model=\"simpleForm.inputRadio\"><span>{{gen}}</span>\n" +
    "                            </label>\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "\n" +
    "                    <div class=\"control-group\">\n" +
    "                        <label class=\"control-label\">select</label>\n" +
    "                        <div class=\"controls\">\n" +
    "                            <select required ng-model=\"simpleForm.select\" ng-options=\"customer.userId as customer.name group by customer.gender for customer in customers\">\n" +
    "                                <option value=\"\">nobody</option>\n" +
    "                            </select>\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "\n" +
    "                    <div class=\"control-group\">\n" +
    "                        <label class=\"control-label\">multiple select</label>\n" +
    "                        <div class=\"controls\">\n" +
    "                            <select size=\"6\" multiple ng-multiple=\"true\" ng-model=\"simpleForm.multiSelect\" ng-options=\"customer.name for customer in customers\"  popover=\"use ctrl/shift to multiple selection\" popover-trigger=\"focus\" popover-append-to-body=\"true\" popover-placement=\"right\">\n" +
    "                            </select>\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "\n" +
    "                    <div class=\"control-group\">\n" +
    "                        <label class=\"control-label\">button</label>\n" +
    "                        <div class=\"controls\">\n" +
    "                            <button class=\"btn btn-primary\" ng-disabled=\"!simple.$valid\" ng-click=\"alertValues();\">confirm</button>\n" +
    "                            <button class=\"btn btn-link\" ng-click=\"emptyAll();\">empty all</button>\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "                </div>\n" +
    "                <div class=\"span6\">\n" +
    "                    <table class=\"table\">\n" +
    "                        <thead>\n" +
    "                            <tr>\n" +
    "                                <th style=\"width:80px\">item</th>\n" +
    "                                <th>valuse</th>\n" +
    "                            </tr>\n" +
    "                        </thead>\n" +
    "                        <tbody>\n" +
    "                            <tr>\n" +
    "                                <td>inputText</td>\n" +
    "                                <td>{{simpleForm.inputText}}</td>\n" +
    "                            </tr>\n" +
    "                            <tr>\n" +
    "                                <td>textarea</td>\n" +
    "                                <td>{{simpleForm.textarea}}</td>\n" +
    "                            </tr>\n" +
    "                            <tr>\n" +
    "                                <td>inputCheckbox</td>\n" +
    "                                <td>{{simpleForm.inputCheckbox}}</td>\n" +
    "                            </tr>\n" +
    "                            <tr>\n" +
    "                                <td>inputRadio</td>\n" +
    "                                <td>{{simpleForm.inputRadio}}</td>\n" +
    "                            </tr>\n" +
    "                            <tr>\n" +
    "                                <td>select</td>\n" +
    "                                <td>{{simpleForm.select}}</td>\n" +
    "                            </tr>\n" +
    "                            <tr>\n" +
    "                                <td>select</td>\n" +
    "                                <td>{{simpleForm.multiSelect | json}}</td>\n" +
    "                            </tr>\n" +
    "                        </tbody>\n" +
    "                    </table>\n" +
    "            </div>\n" +
    "        </div>\n" +
    "        </fieldset>\n" +
    "    </div>\n" +
    "</div>");
}]);

angular.module("demo/demo.http.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("demo/demo.http.tpl.html",
    "<h1>Demo: http</h1>\n" +
    "<ul ng-show=\"orgs.length\">\n" +
    "    <li ng-repeat=\"org in orgs\"><a href=\"{{org.url}}\">{{org.name}}</a></li>\n" +
    "  </ul>\n" +
    "<div ng-hide=\"orgs.length\">There's no orgnization yet,join now!</div>");
}]);

angular.module("demo/demo.inputText.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("demo/demo.inputText.tpl.html",
    "<div>\n" +
    "    <form class=\"form-horizontal\">\n" +
    "        <fieldset>\n" +
    "            <legend>\n" +
    "                input-text\n" +
    "            </legend>\n" +
    "            <div class=\"row\">\n" +
    "                <div class=\"span12\">\n" +
    "                    <div class=\"control-group\">\n" +
    "                        <label class=\"control-label\">placeHolder</label>\n" +
    "                        <div class=\"controls\">\n" +
    "                            <input type=\"text\" id=\"inputTextPlaceholder\" yt-placeholder=\"请输入一些文本\">\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "                </div>\n" +
    "            </div>\n" +
    "        </fieldset>\n" +
    "    </form>\n" +
    "</div>");
}]);

angular.module("demo/demo.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("demo/demo.tpl.html",
    "<div ui-view=\"demoContainer\">\n" +
    "    <h1>it's demo section</h1>\n" +
    "</div>");
}]);

angular.module("demo/leftNav.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("demo/leftNav.tpl.html",
    "<div class=\"well\">\n" +
    "    <ul class=\"nav nav-list\">\n" +
    "        <li class=\"nav-header\">some demo</li>\n" +
    "        <li><a ui-sref=\"demo.http\">http</a></li>\n" +
    "        <li><a ui-sref=\"demo.form\">form</a></li>\n" +
    "        <li><a ui-sref=\"demo.inputText\">inputText</a></li>\n" +
    "    </ul>\n" +
    "</div>");
}]);

angular.module("home/home.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("home/home.tpl.html",
    "<div class=\"row\">\n" +
    "    <div class=\"col-sm-12\">\n" +
    "        <h1>\n" +
    "            i'am homepage\n" +
    "        </h1>\n" +
    "    </div>\n" +
    "</div>");
}]);

angular.module("http/http.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("http/http.tpl.html",
    "<div class=\"http\">\n" +
    "  <!-- content here -->\n" +
    "  http\n" +
    "</div>");
}]);

angular.module("leftNav/testLeftNav.tpl.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("leftNav/testLeftNav.tpl.html",
    "<h1>i'm left nav</h1>");
}]);
