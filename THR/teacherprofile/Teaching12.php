<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$nicUpdate = $_SESSION['NIC'];
$NICUser = $id;

?>

<div class="main_content_inner_block">
    <div class="mcib_middle1">
        <h1>Subject Entry form</h1>
        <form>
            <div>
                <h3>Teaching Subject for Most Hours</h3>
                <table>
                    <tr>
                        <td>
                            <label for="medium1">Medium:</label>
                        </td>
                        <td>
                            <select id="medium1" name="medium1" onchange="changetxt()">
                                <option value="volvo">Volvo</option>
                                <option value="saab">Saab</option>
                                <option value="fiat">Fiat</option>
                                <option value="audi">Audi</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Grade Span</td>
                        <td>
                            <select id="grade1" name="grade1">
                                <option value="volvo">Volvo</option>
                                <option value="saab">Saab</option>
                                <option value="fiat">Fiat</option>
                                <option value="audi">Audi</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Subject</td>
                        <td>
                            <select id="subject1" name="subject1">
                                <option value="volvo">Volvo</option>
                                <option value="saab">Saab</option>
                                <option value="fiat">Fiat</option>
                                <option value="audi">Audi</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>If Other Please Specify: </td>
                        <td>
                            <input type="text" name="otherTch1" id="otherTch1">
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
    function changetxt() {
        var medium = document.getElementById("medium1").value
        var subject = document.getElementById("subject1").value
        var grade = document.getElementById("grade1").value
        // console.log(medium)
        // console.log(subject)
        // console.log(grade)

        
    }
</script>