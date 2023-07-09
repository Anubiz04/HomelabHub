<!DOCTYPE HTML>
<html>
<head>
    <title>The Homelab Hub</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
    <link rel="stylesheet" href="assets/css/main.css"/>
    <noscript>
        <link rel="stylesheet" href="assets/css/noscript.css"/>
    </noscript>
</head>
<body class="is-preload">
<!-- Wrapper -->
<div id="wrapper">

    <?php
    include "header.php";
    $pdo = new PDO('mysql:host=localhost;dbname=homelabhub', 'root', '');
    ?>
    <!-- Main -->
    <div id="main">
        <div class="inner">
            <header>
                <h1>Homelab Hub</h1>
                <p>An OpenSource Dashboard for your Homelab</p>
            </header>
            <section class="tiles">
                <?php
                //Submit changes to db
                if (isset($_POST['save'])) {
                    $data = [
                        'name' => $_POST['sitename2'],
                        'url' => $_POST['url2'],
                        'id' => $_POST['id'],
                    ];
                    $sql = "UPDATE sites SET Name=:name, URL=:url WHERE id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($data);
                }

                //Check if a new site is submitted and push in db
                if (isset($_POST['acpt'])) {
                    $statement = $pdo->prepare("INSERT INTO sites (Name, URL) VALUES (:sitename, :URL)");
                    $result = $statement->execute(array('sitename' => $_POST['sitename'], 'URL' => $_POST['url']));

                    if (!$result) {
                        echo 'Beim Absenden ist leider ein Fehler aufgetreten<br>';
                    } else {
                    }
                }

                //Check if del is pressed
                if (isset($_POST['del'])) {
                    $statement = $pdo->prepare("DELETE FROM sites WHERE ID = :id");
                    $result = $statement->execute(array('id' => $_POST['id']));

                    if (!$result) {
                        echo 'Beim Absenden ist leider ein Fehler aufgetreten<br>';
                    } else {
                    }
                }

                //Edit Popup
                if (isset($_GET['edit'])) {
                    if ($_GET['edit'] != "exit") {
                        $sql = "SELECT * FROM sites WHERE ID = " . $_GET['edit'];
                        //echo $sql;
                        foreach ($pdo->query($sql) as $row) {
                            echo '
                <div class="overlay" xmlns="http://www.w3.org/1999/html">
                <div class="popup">
                    <h2>Edit Page</h2>
                    <div>
                        Edit the Name or URL or delete the Site<br>Make sure to include the "http[s]://" in front and the "/" at the end of the URL
                    </div>
                    <br>
                    <div class="col-12 gtr-uniform">
                    <form method="post" action="?edit=exit">
                        <div class="col-6 ">
		                <input type="text" name="id" id="id" value="' . $row['ID'] . '" readonly/>
						</div>
                        <div class="col-6 ">
		                <input type="text" name="sitename2" id="sitename2" value="' . $row['Name'] . '" placeholder="Display Name" />
						</div>
						<div class="col-6 ">
						<input type="text" name="url2" id="url2" value="' . $row['URL'] . '" placeholder="http(s)://IP-or-Domain.whatever/" />
						</div>
						</div>                    
                        <input class="button" type="submit" name="save" value="OK" style="color: #8d8d8d; background-color: rgb(154,154,154)">
                        <input class="button" type="submit" name="exit" value="Cancel" style=" color: #8d8d8d; background-color: rgb(154,154,154)">
                        <input class="button" type="submit" name="del" value="Delete" style=" color: #8d8d8d; background-color: rgb(243,150,150)">
                    </form> 
                </div>
            </div>
                ';
                        }
                    }
                }

                //Loop that displays all the sites
                $sql = "SELECT * FROM sites ORDER BY id ASC";
                foreach ($pdo->query($sql) as $row) {
                    echo '<article class="style2">
                            <span class="image">
                                <img src="' . $row['URL'] . 'favicon.ico" alt="" />
                            </span> 
                            ';
                    echo '<a target="_blank" rel="noopener noreferrer" href="' . $row['URL'] . '">
                          <h2 style="font-size: 50px; color: #333333">' . $row['Name'] . '</h2>
                          </a>
                          <a href="?edit=' . $row['ID'] . '" class="icon style2 fa-solid fa-pen-to-square"></a>
                          </article>';
                }

                //New site popup
                if (isset($_GET['new'])) {
                    if ($_GET['new'] == "true") {

                        echo '
                <div class="overlay" xmlns="http://www.w3.org/1999/html">
                <div class="popup">
                    <h2>Add a new Site</h2>
                    <div>
                        Enter a URL and a Display Name to add a new Site<br>Make sure to include the "http[s]://" in front and the "/" at the end of the URL
                    </div>
                    <br>
                    <div class="col-12 gtr-uniform">
                    <form method="post" action="?new=false">
                        <div class="col-6 ">
		                <input type="text" name="sitename" id="sitename" value="" placeholder="Display Name" />
						</div>
						<div class="col-6 ">
						<input type="text" name="url" id="url" value="" placeholder="http(s)://IP-or-Domain.whatever/" />
						</div>
						</div>                    
                        <input class="button" type="submit" name="acpt" value="OK" style="color: rgba(117,117,117,0.83); background-color: rgb(154,154,154)">
                        <input class="button" type="submit" name="cancel" value="Cancel" style=" border-color: #8d8d8d; color: #8d8d8d; background-color: rgb(154,154,154)">
                    </form> 
                </div>
            </div>
                ';
                    }
                }
                ?>

                </article>
                <article class="style4">
                <span class="image">
                    <img src="images/verlauf.jpg" alt=""/>
                </span>
                    <a href="?new=true">
                        <h2>Add</h2>
                        <div class="content">
                            <p>Click to add a new Site</p>
                        </div>
                    </a>
                </article>
            </section>
        </div>
    </div>

<?php include "footer.php";?>
</body>
</html>