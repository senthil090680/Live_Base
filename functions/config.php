<?php
session_start();
ob_start();
include("../include/header.php");
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
?>

<script type="text/javascript" src="../js/jquery1.js"></script>
<style>

    fieldset{
        margin:20px;
        padding:10px;
    }

    td{
        padding:5px;
    }

    .buttons{

        width:90px;

    }

</style>
<div id="mainarea">
    <div style="width:49%;float:left;">
        <fieldset>
            <legend> Process </legend>
            <table width="100%">
                <tr>
                    <td>
                        <span>Process</span>
                    </td>
                    <td>
                        <select id="process">

                            <option value="ub">Upload to HOST </option>
                        </select>
                    </td>

                </tr>
                <tr>
                    <td>
                        <span>Option</span>
                    </td>	

                    <td>
                        <select id="option" onchange="manual();" style="float:left;">
                            <option value="auto">Auto Schedule </option>
                            <option value="ondemand">On-Demand </option>
                            <option value="manual">Manual </option>
                        </select>


                        <div style="width:340px;"><a style="display:none;float:right;" onclick="update()" id="manual" href="#">Click here to ON-Demand update</a></div>			
                    </td>
                </tr>


            </table>
        </fieldset>	

    </div>
    <div style="float:left;">
        <fieldset>
            <legend>Frequency</legend>
            <table>
                <tr>
                    <td>
                        <span>Frequency</span>
                    </td>
                    <td>
                        <select onchange="freqChange();" id="frequency">
                            <option value="weekly">Weekly </option>
                            <option value="daily">Daily</option>
                            <option value="hourly">Hourly</option>
                            <option value="halfhour">Half - Hourly </option>										
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Day</span>
                    </td>
                    <td>
                        <select  id="day">
                            <option value="SUN">Sunday</option>
                            <option value="MON">Monday</option>
                            <option value="TUE">Tuesday</option>
                            <option value="WED">Wednesday</option>
                            <option value="THU">Thursday</option>
                            <option value="FRI">Friday</option>
                            <option value="SAT">Saturday</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Start Date </span>
                    </td>
                    <td>
                        <input  style="text-align:center;" type="text" value="<?php echo date('Y-m-d'); ?>" id="sd">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Start Time </span>
                    </td>
                    <td>
                        <input style="text-align:center;"  type="text" value="<?php echo date("H:m:s"); ?>" id="st">
                    </td>
                </tr>
            </table>

        </fieldset>
    </div>

    <div style="position:relative;top:50px;width:100px;float:right">
        <input type="button" class = "buttons" value="save" onclick="load()" />
    </div>


    <script type="text/javascript">

                            /**
                             Weekly frequency uses day feature.
                             
                             */

                            function freqChange()
                            {

                                var week = document.getElementById("frequency");
                                var day = document.getElementById("day");

                                if (week.value == "weekly")
                                {
                                    day.disabled = false;
                                }
                                else
                                {
                                    day.disabled = true;
                                }

                            }


                            function update()
                            {
                                var option = document.getElementById("option");
                                var process = document.getElementById("process");

                                try {
                                    xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
                                }
                                catch (e)
                                {
                                }
                                if (option.value == "ondemand")
                                {

                                    xmlhttp.open("GET", "http://sfa.fmclgrp.com/RetailKd/Host/functions/scheduleUpload.php");

                                }

                                xmlhttp.send("null");
                                xmlhttp.onreadystatechange = function()
                                {
                                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                                    {
                                        alert(xmlhttp.responseText);
                                    }
                                }
                            }

                            function manual()
                            {
                                var option = document.getElementById("option");
                                var manual = document.getElementById("manual");
                                var process = document.getElementById("process");
                                if (option.value == "ondemand")
                                {
                                    manual.innerHTML = "Click here to ON-Demand update";
                                    manual.style.display = 'block';
                                }
                                else if (option.value == "manual")
                                {
                                    if (process.value == "ub")
                                    {
                                        manual.innerHTML = "Click here to Manual update";
                                        manual.style.display = 'block';
                                    }
                                    else {
                                        manual.style.display = 'none';
                                    }
                                }
                                else
                                {
                                    manual.style.display = "none";
                                }


                            }

                            function load()
                            {

                                var process = document.getElementById("process");
                                var option = document.getElementById("option");
                                var frequency = document.getElementById("frequency");
                                var day = document.getElementById("day");
                                var sd = document.getElementById("sd");
                                var st = document.getElementById("st");
                                var flag = true;

                                if (option.value == "auto")
                                {
                                    if (st.value == "" || sd.value == "")
                                    {
                                        var result = confirm("Are you sure to Shedule without Start Date / Start Time");
                                        if (result == true)
                                        {
                                            flag = true;
                                        }
                                        else
                                        {
                                            flag = false;
                                        }
                                    }
                                }
                                if (flag == true)
                                {
                                    var posting = $.post("save.php", {process: process.value, option: option.value, frequency: frequency.value, day: day.value, sd: sd.value, st: st.value});

                                    posting.done(function(data) {
                                        alert(data);
                                    });
                                }
                            }


    </script>
</div>

<?php include('../include/footer.php'); ?>

