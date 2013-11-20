<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    /**
    * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
    * GNU General Public License
    * Copyright 2002,2003,2004,2005 Elpidio Latorilla
    * , elpidio@care2x.org
    *
    * See the file "copy_notice.txt" for the licence notice
    */
    define('NO_2LEVEL_CHK',1);
    $lang_tables[]='person.php';
    define('LANG_FILE','aufnahme.php');
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'global_conf/inc_remoteservers_conf.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');
    require_once($root_path.'include/care_api_classes/class_person.php');
    $person=& new Person($pid);
    $person->preloadPersonInfo();
?>
<?php html_rtl($lang); ?>
<head>
    <title>
        <?php 
            echo $person->LastName().' '.$person->FirstName().' ['.formatDate2Local($person->Birthdate(),$date_format).']';  
        ?>
    </title>
    <link rel="stylesheet" href="<?php echo $root_path.'gui/css/themes/default/default.css'; ?>" type="text/css">
        <?php echo setCharSet(); ?>
</head>
<body onLoad="if (window.focus) window.focus()">
<table>
    <tr>
        <td colspan="2" align="center">
            <font size=4 face="verdana,arial" color="darkred">
                <b>
                <?php echo $LDPatientRegister?>
                </b>
            </font>
        </td>
    </tr>
    <tr >
        <td valign="top">
            <table class="submenu_frame">
                <tbody class="submenu">
                <tr>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php 
                            echo $LDLastName.' & '.$LDFirstName.': '; 
                        ?>
                        </font>
                    </td>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php 
                            echo $person->LastName().' '.$person->FirstName(); 
                        ?>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php  
                            echo $LDBday.': ';
                        ?>
                        </font>
                    </td>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php  
                            echo formatDate2Local($person->Birthdate(),$date_format);
                        ?>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $LDAddress.': ';
                        ?>
                        </font>
                    </td>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $person->StreetNr().', '.$person->StreetName();
                        ?>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $LDPhone.': ';
                        ?>
                        </font>
                    </td>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $person->FirstPhoneNumber();
                        ?>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $LDEmail.': ';
                        ?>
                        </font>
                    </td>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $person->EmailAddress();
                        ?>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $LDSex.': ';
                        ?>
                        </font>
                    </td>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            $sex=$person->Sex();
                            switch ($sex){
                                case 'm':
                                    echo $LDMale;
                                    break;
                                default:
                                    echo $LDFemale;
                                    break;
                            }
                        ?>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $LDBloodGroup.': ';
                        ?>
                        </font>
                    </td>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $person->BloodGroup();
                        ?>
                        </font>
                    </td>
                </tr>
                <?php
                    $death=$person->DeathDate();
                    if($death){
                ?>
                <tr>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $LDDeathDate.': ';
                        ?>
                        </font>
                    </td>
                    <td bgcolor="white">
                        <font size=2 face="verdana,arial">
                        <?php
                            echo $person->DeathDate();
                        ?>
                        </font>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </td>
        <td>
            <?php
                if($person->PhotoFilename()&&file_exists($root_path.'uploads/photos/registration/'.$person->PhotoFilename())){
            ?>
                <img src="<?php echo $root_path ?>uploads/photos/registration/<?php echo $person->PhotoFilename(); ?>">
            <?php
                }
            ?>
        </td>
    </tr>
</table>
</body>
</html>
