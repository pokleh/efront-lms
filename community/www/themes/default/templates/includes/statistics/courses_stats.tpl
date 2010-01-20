    {capture name='course_statistics'}
            <table class = "statisticsTools statisticsSelectList">
                <tr><td class = "labelCell">{$smarty.const._CHOOSECOURSE}:</td>
                    <td class = "elementCell" colspan = "4">
                        <input type = "text" id = "autocomplete" class = "autoCompleteTextBox"/>
                        <img id = "busy" src = "images/16x16/clock.png" style="display:none;" alt = "{$smarty.const._LOADING}" title = "{$smarty.const._LOADING}"/>
                        <div id = "autocomplete_courses" class = "autocomplete"></div>&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr><td></td>
                 <td class = "infoCell" colspan = "4">{$smarty.const._STARTTYPINGFORRELEVENTMATCHES}</td></tr>
    {if !isset($T_COURSE_INFO)}
      </table>
 {else}
    <tr><td class = "labelCell">{$smarty.const._FILTERS}:</td>
     <td class = "filter">
                        <select style = "vertical-align:middle" name = "user_filter" onchange = "if (this.options[this.selectedIndex].value != '') document.location='{$smarty.server.PHP_SELF}?ctg=statistics&option=course&{if (isset($smarty.get.tab))}&tab={$smarty.get.tab}{else}&tab=users{/if}&sel_course={$T_COURSE_ID}{if (isset($smarty.get.branch_filter))}&branch_filter={$smarty.get.branch_filter}{/if}{if (isset($smarty.get.group_filter))}&group_filter={$smarty.get.group_filter}{/if}&user_filter='+this.options[this.selectedIndex].value;">
                                <option value = "1"{if !$smarty.get.user_filter || $smarty.get.user_filter == 1}selected{/if}>{$smarty.const._ACTIVEUSERS}</option>
                                <option value = "2"{if $smarty.get.user_filter == 2}selected{/if}>{$smarty.const._INACTIVEUSERS}</option>
                                <option value = "3"{if $smarty.get.user_filter == 3}selected{/if}>{$smarty.const._ALLUSERS}</option>
                        </select>
                    </td><td class = "filter">
                        <select style = "vertical-align:middle" name = "group_filter" onchange = "if (this.options[this.selectedIndex].value != '') document.location='{$smarty.server.PHP_SELF}?ctg=statistics&option=course&{if (isset($smarty.get.tab))}&tab={$smarty.get.tab}{else}&tab=users{/if}&sel_course={$T_COURSE_ID}{if (isset($smarty.get.branch_filter))}&branch_filter={$smarty.get.branch_filter}{/if}{if (isset($smarty.get.user_filter))}&user_filter={$smarty.get.user_filter}{/if}&group_filter='+this.options[this.selectedIndex].value;">
                                <option value = "-1" class = "inactiveElement" {if !$smarty.get.group_filter}selected{/if}>{$smarty.const._SELECTGROUP}</option>
                            {foreach name = "group_options" from = $T_GROUPS item = 'group' key='id'}
                                <option value = "{$group.id}" {if $smarty.get.group_filter == $group.id}selected{/if}>{$group.name}</option>
                            {/foreach}
                        </select>
                    </td>
                 <td id = "right">
                        {$smarty.const._EXPORTSTATS}
                        <a href = "{$T_BASIC_TYPE}.php?ctg=statistics&option=course&sel_course={$T_COURSE_ID}&excel=1&group_filter={$smarty.get.group_filter}&branch_filter={$smarty.get.branch_filter}">
                            <img src = "images/file_types/xls.png" title = "{$smarty.const._XLSFORMAT}" alt = "{$smarty.const._XLSFORMAT}" />
                        </a>
                        <a href = "{$T_BASIC_TYPE}.php?ctg=statistics&option=course&sel_course={$T_COURSE_ID}&pdf=1&group_filter={$smarty.get.group_filter}&branch_filter={$smarty.get.branch_filter}">
                            <img src = "images/file_types/pdf.png" title = "{$smarty.const._PDFFORMAT}" alt = "{$smarty.const._PDFFORMAT}" />
                        </a>
                    </td></tr>
         </table>
      <br/>
            <table class = "statisticsGeneralInfo">
                <tr class = "{cycle name = 'course_common_info' values = 'oddRowColor, evenRowColor'}">
                    <td class = "labelCell">{$smarty.const._NAME}:</td>
                    <td class = "elementCell">{$T_COURSE_INFO.name}</td></tr>
                </tr>
                <tr class = "{cycle name = 'course_common_info' values = 'oddRowColor, evenRowColor'}">
                    <td class = "labelCell">{$smarty.const._DIRECTION}:</td>
                    <td class = "elementCell">{$T_COURSE_INFO.direction}</td></tr>
                </tr>
                <tr class = "{cycle name = 'course_common_info' values = 'oddRowColor, evenRowColor'}">
                    <td class = "labelCell">{$smarty.const._LESSONS}:</td>
                    <td class = "elementCell">{$T_COURSE_INFO.lessons}</td></tr>
                </tr>
                <tr class = "{cycle name = 'course_common_info' values = 'oddRowColor, evenRowColor'}">
                    <td class = "labelCell">{$smarty.const._STUDENTS}:</td>
                    <td class = "elementCell">{$T_COURSE_INFO.students}</td></tr>
                </tr>
                <tr class = "{cycle name = 'course_common_info' values = 'oddRowColor, evenRowColor'}">
                    <td class = "labelCell">{$smarty.const._PROFESSORS}:</td>
                    <td class = "elementCell">{$T_COURSE_INFO.professors}</td></tr>
                </tr>
                <tr class = "{cycle name = 'course_common_info' values = 'oddRowColor, evenRowColor'}">
                    <td class = "labelCell">{$smarty.const._PRICE}:</td>
                    <td class = "elementCell">{$T_COURSE_INFO.price}</td></tr>
                </tr>
                <tr class = "{cycle name = 'course_common_info' values = 'oddRowColor, evenRowColor'}">
                    <td class = "labelCell">{$smarty.const._LANGUAGE}:</td>
                    <td class = "elementCell">{$T_COURSE_INFO.language}</td></tr>
                </tr>
   </table>
    {/if}
    {if $T_COURSE_USERS_STATS|@sizeof > 0 || $T_COURSE_PROFESSORS_STATS|@sizeof > 0 || $T_COURSE_LESSON_STATS|@sizeof > 0}
        <div class = "tabber">
        {if $T_COURSE_USERS_STATS|@sizeof > 0 || $T_COURSE_PROFESSORS_STATS|@sizeof > 0}
            <div class = "statisticsDiv tabbertab {if (isset($smarty.get.tab) &&  $smarty.get.tab == 'users')} tabbertabdefault{/if}" title = "{$smarty.const._USERS}">
   {if $T_COURSE_USERS_STATS|@sizeof > 0}
                <table class = "statisticsTools">
                    <tr><td>{$smarty.const._STUDENTS}:</td></tr>
                </table>
                <table class = "sortedTable">
                    <tr>
                        <td class = "topTitle" style = "width:300px">{$smarty.const._USER}</td>
                        <td class = "topTitle">{$smarty.const._COURSEROLE}</td>
                        <td class = "topTitle centerAlign">{$smarty.const._COMPLETED}</td>
                        <td class = "topTitle centerAlign">{$smarty.const._SCORE}</td>
                        <td class = "topTitle noSort centerAlign">{$smarty.const._OPTIONS}</td>
                    </tr>
                {foreach name = 'student_list' key = "login" item = "info" from = $T_COURSE_USERS_STATS}
                    <tr class = "{cycle values = "oddRowColor, evenRowColor"} {if !$info.active}deactivatedTableElement{/if}">
                        <td><a href = "{$smarty.server.PHP_SELF}?ctg=statistics&option=user&sel_user={$login}">#filter:login-{$login}#</a></td>
                        <td>{$T_ROLES[$info.role]}</td>
                        <td class = "centerAlign">
                            {if $info.completed}
                                <img src = "images/16x16/success.png" alt = "{$smarty.const._YES}" title = "{$smarty.const._YES}" border = "0" />
                            {else}
                                <img src = "images/16x16/forbidden.png" alt = "{$smarty.const._NO}" title = "{$smarty.const._NO}" border = "0" />
                            {/if}
                        </td>
                        <td class = "progressCell">
                            <span style = "display:none">{$info.score*1000}</span>
                            <span class = "progressNumber">#filter:score-{$info.score}#%</span>
                            <span class = "progressBar" style = "width:{$info.score}px;">&nbsp;</span>&nbsp;
                        </td>
                        <td class = "centerAlign">
       <a href = "{$smarty.server.PHP_SELF}?ctg=statistics&option=user&sel_user={$login}&specific_course_info=1&course={$T_COURSE_ID}&popup=1" onclick = "eF_js_showDivPopup('{$smarty.const._DETAILS}', 2)" target = "POPUP_FRAME">
        <img src = "images/16x16/information.png" title = "{$smarty.const._DETAILS}" alt = "{$smarty.const._DETAILS}"></a>
                        </td>
                    </tr>
                {/foreach}
          </table>
             <br/>
            {/if}
   {if $T_COURSE_PROFESSORS_STATS|@sizeof > 0}
                <table class = "statisticsTools">
                    <tr><td>{$smarty.const._PROFESSORS}:</td></tr>
                </table>
    <table class = "sortedTable">
                    <tr>
                        <td class = "topTitle" style = "width:300px">{$smarty.const._USER}</td>
                        <td class = "topTitle">{$smarty.const._COURSEROLE}</td>
                    </tr>
                    {foreach name = 'professor_list' item = 'info' key = "login" from = $T_COURSE_PROFESSORS_STATS}
                        <tr class = "{cycle name = 'cprofessor_list' values = 'oddRowColor, evenRowColor'} {if !$info.active}deactivatedTableElement{/if}">
                            <td> <a href = "{$T_BASIC_TYPE}.php?ctg=statistics&option=user&sel_user={$login}">#filter:login-{$login}#</a></td>
                            <td>{$T_ROLES[$info.role]}</td>
                        </tr>
                    {/foreach}
    </table>
   {/if}
            </div>
        {/if}
        {if $T_COURSE_LESSON_STATS|@sizeof > 0}
            <div class = "statisticsDiv tabbertab {if (isset($smarty.get.tab) &&  $smarty.get.tab == 'lessons')} tabbertabdefault{/if}" title = "{$smarty.const._LESSONS}">
                <table class = "sortedTable">
                    <tr>
                        <td class = "topTitle">{$smarty.const._LESSON}</td>
                        <td class = "topTitle centerAlign">{$smarty.const._CONTENT}</td>
                        {if $T_CONFIGURATION.disable_tests != 1}
       <td class = "topTitle centerAlign">{$smarty.const._TESTS}</td>
                        {/if}
      {if $T_CONFIGURATION.disable_projects != 1}
       <td class = "topTitle centerAlign">{$smarty.const._PROJECTS}</td>
      {/if}
                    </tr>
                {foreach name = 'lesson_list' key ="id" item = "info" from = $T_COURSE_LESSON_STATS}
                    <tr class = "{cycle values = "oddRowColor, evenRowColor"} {if !$info.active}deactivatedTableElement{/if}" >
                        <td><a href = "{$T_BASIC_TYPE}.php?ctg=statistics&option=lesson&sel_lesson={$id}">{$info.name}</a></td>
                        <td class = "centerAlign">{$info.content}</td>
      {if $T_CONFIGURATION.disable_tests != 1}
       <td class = "centerAlign">{$info.tests}</td>
      {/if}
      {if $T_CONFIGURATION.disable_projects != 1}
       <td class = "centerAlign">{$info.projects}</td>
      {/if}
                    </tr>
                {/foreach}
    </table>
   </div>
        {/if}
        </div>
    {/if}
    {/capture}
    {if $T_COURSE_NAME != ""}
  {eF_template_printBlock title = "`$smarty.const._REPORTSFORCOURSE` <span class='innerTableName'>&quot;`$T_COURSE_NAME`&quot;</span>" data = $smarty.capture.course_statistics image = '32x32/courses.png'}
    {else}
     {eF_template_printBlock title = $smarty.const._COURSESTATISTICS data = $smarty.capture.course_statistics image = '32x32/courses.png'}
 {/if}