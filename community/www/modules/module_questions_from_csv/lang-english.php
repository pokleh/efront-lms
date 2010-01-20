<?php
/* This file defines the constants for the module in English */
define("_MODULE_QUESTIONS_TESTQUESTIONSUPLOADING","Test questions uploading");
define("_MODULE_QUESTIONS_IMPORTUSERSHISTORYFROMXLSFILE", "Import test questions from csv file");
define("_MODULE_QUESTIONS_HISTORYRECORDS" , "test questions");
define("_MODULE_QUESTIONS_LOGINCOLUMNTITLE","Title for column with eFront user logins");
define("_MODULE_QUESTIONS_DATECOLUMNTITLE","Title for column with event dates");
define("_MODULE_QUESTIONS_HANDLINGFORNOTEXISTINGLOGINS","Options for existing questions");
define("_MODULE_QUESTIONS_IMPORTINTO","Import into");
define("_MODULE_QUESTIONS_INCLUDECOLUMNTITLESINTOEVENTDESCRIPTION","Include column titles into event description");
define("_MODULE_QUESTIONS_USERHISTORY","User history");
define("_MODULE_QUESTIONS_USEREVALUATIONS","User evaluations");
define("_MODULE_QUESTIONS_OMMITRECORDSWHOSELOGINDOESNOTEXIST","Ommit existing questions");
define("_MODULE_QUESTIONS_ADDNEWRECORDSWHOSELOGINDOESNOTEXIST","Replace existing questions");
define("_MODULE_QUESTIONS_THESUPPLIEDTITLEFORCOLUMNWITHEFRONTUSERLOGINSDOESNOTEXIST", "The supplied title for column with eFront user logins does not exist in the first row of your file. Please make sure that the two titles are exactly the same.");
define("_MODULE_QUESTIONS_THERECORDSHAVEBEENOMMITED","The questions that could not be imported are");
define("_MODULE_QUESTIONS_THEFOLLOWINGUSERSHAVEBEENINSERT","The following users (with password 'password') have been inserted into the system");
define("_MODULE_QUESTIONS_THESUPPLIEDTITLEFORCOLUMNDATEDOESNOTEXIST","The supplied title for column with event dates does not exist in the first row of your file. Please make sure that the two titles are exactly the same. <br>Otherwise, if no such column exists, insert a blank value");
define("_MODULE_QUESTIONS_BOTTOMNOTE","You are adviced to backup your database before the import, especially if you are planning to add non-existing questions into the system. Though there exists no danger whatsoever to current system data, wrong parameter definitions might lead to wrong data insertions, that would then require manual removal.");
define("_MODULE_QUESTIONS_PLEASECONFIGUREDATE", "Please configure the spreadsheet to show dates in the form dd/mm/yyyy or dd-mm-yyyy");

define("_MODULE_QUESTIONS_QUESTIONTYPEISWRONG", "The question type is wrong");
define("_MODULE_QUESTIONS_QUESTIONDIFFICULTYISWRONG","The question difficulty is wrong");
define("_MODULE_QUESTIONS_QUESTIONLESSONUNITDOESNOTEXIST","The lesson-unit combination does not exist");
define("_MODULE_QUESTIONS_WRONGQUESTIONTIME","Wrong question time value");
define("_MODULE_QUESTIONS_NOOPTIONSDEFINEDFORMULTIPLECHOICE","No options defined for multiple choice question");
define("_MODULE_QUESTIONS_NOCORRECTANSWERSDEFINEDFORMULTIPLECHOICE","No correct answers defined for multiple choice question");
define("_MODULE_QUESTIONS_NOQUESTIONTEXT","No text defined for the question");
define("_MODULE_QUESTIONS_NOCORRECTANSWERSDEFINEDFORTRUEFALSE","No correct answers defined for true/false type question");
define("_MODULE_QUESTIONS_NOEMPTYSPACESDEFINEDFOREMPTYSPACEQUESTION","No empty spaces defined for empty space question");
define("_MODULE_QUESTIONS_WRONGAMOUNTOFANSWERSINEMPTYSPACES","Wrong amount of answers for empty spaces question");
define("_MODULE_QUESTIONS_NOANSWERSDEFINEDFOREMPTYSPACES","No answers defined for empty spaces question");


define("_MODULE_QUESTIONS_SAMPLEANSWERFILENOTFOUND","Sample answer file not found");

define("_MODULE_QUESTIONS_UPLOADAZIPFILEIFYOUWANTTOUPLOADQUESTIONFILES","You need to upload a zip file if you want to upload question files");

define("_MODULE_QUESTIONS_QUESTIONEXISTSALREADY","Question already exists");

define("_MODULE_QUESTIONS_LINE","Line");
define("_MODULE_QUESTIONS_WRONGINPUTFILETYPE","Wrong input file type");

define("_MODULE_QUESTIONS_REPORTALREADYEXISTINGQUESTIONS","Report already existing questions");
define("_MODULE_QUESTIONS_QUESTIONREPLACEDPREVIOUSEXISTING","Question replaced previous existing one with the same text");
define("_MODULE_QUESTIONS_PLEASESELECTALESSON","Please select a lesson");


?>