<?php
require_once("../../config.php");

$allCekFolderGet = $_GET['allCekFolder'];
$allCekFileGet = $_GET['allCekFile'];
$userIdSelectGet = $_GET['userIdSelect'];
$allIdFoldRefGet = $_GET['allIdFoldRef'];
$idFoldRefGet = $_GET['idFoldRef'];
//$cekFoldPisah = explode("*****", $allCekFolderGet);
//$bykCekFold = sizeof($cekFoldPisah);

//$folderPisah = $cekFoldPisah[0];
//$folderPisah = $allCekFolderGet;

$idFoldLast = $CFolder->idFoldLast("1", "");
// SETELAH BIKIN FOLDER PUBLIC DENGAN IDFOLD = $idFoldLast MAKA LANJUTKAN KONVERSI

?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>

<style>

</style>

<script type="text/javascript">
//note this is JScript, not javascript. Thus the 'type="text/JScript"' in the script tags 
function daftarFolderFile(lokasiFolder) {
    // >>>>> JIKA IDFOLDREF (IDFOLDLAST) BERDASARKAN MENUFOLD DI PARENT
    /*var splitLokasiFolder = lokasiFolder.split("*****");
    var allIdFoldLast = "<?php echo $idFoldRefGet; ?>";
    namaFolder = splitLokasiFolder[0].replace("-----", "#");
    	
    document.getElementById('allFolder').innerHTML += "<span style=\"text-decoration:underline;\">"+namaFolder+"</span><br>";
    document.getElementById('allFolder').innerHTML += daftarFolder(namaFolder , 1, 0, allIdFoldLast);
    	
    setTimeout(function()
    {
    	addEvents() ;
    },500);*/

    /* // >>>>> JIKA PEMISAHAN BERDASARKAN LOKASI FOLDER YANG DICENTANG BUKAN IDFOLD
    
    var splitLokasiFolder = lokasiFolder.split("*****");
    var pjgSplit = (splitLokasiFolder.length - 1);
    var idFoldLast =  <?php echo ($idFoldLast-1); ?>;
    
    var allIdFoldLast = "";
    var namaFolder = "";
    
    for(var a = 0; a < pjgSplit; a++)
    {
    	idFoldLast = idFoldLast + 1;
    	namaFolder = splitLokasiFolder[a].replace("-----", "#");
    	document.getElementById('allFolder').innerHTML += "<span style=\"text-decoration:underline;\">"+namaFolder+"</span><br>";
    	document.getElementById('allFolder').innerHTML += daftarFolder(namaFolder , '0', a, idFoldLast);
    	
    	allIdFoldLast += idFoldLast+", ";
    }
    
    setTimeout(function()
    {
    	addEvents(a) ;
    },500);*/

    /*document.getElementById('allFolder').innerHTML += "<span style=\"text-decoration:underline;\">"+splitLokasiFolder[0]+"</span><br>";
    document.getElementById('allFolder').innerHTML += daftarFolder(splitLokasiFolder[0], '0');
    
    setTimeout(function()
    {
    	addEvents() ;
    },500);*/
    // >>>>> JIKA PEMISAHAN BERDASARKAN ALLIDFOLDREF YANG DISPLIT KEMUDIAN DILOOPING
    /// LAKUKAN PEMISAHAN DENGAN DELIMETER (*****) PADA $allCekFolderGet YANG BERISI NAMA FOLDER DAN PATH
    var splitLokasiFolder = lokasiFolder.split("*****");
    // LAKUKAN PEMISAHAN DENGAN DELIMETER (-) PADA $allIdFoldRefGet YANG BERISI IDFOLDREF
    var splitIdFoldLast = "<?php echo $allIdFoldRefGet; ?>".split("-");
    var pjgSplit = (splitIdFoldLast.length - 1); // JUMLAH TOTAL IDFOLDREF YANG DIDAPAT SETELAH DI PISAH

    var allIdFoldLast = "";
    var namaFolder = "";
    var tipeKonten = "";
    var fileFold;
    for (var a = 0; a < pjgSplit; a++) {
        // REPLACE NAMA FOLDER DAN PATH YANG MENGANDUNG "#" DENGAN "-----" KARENA NAMA FOLDER DENGAN "#" AKAN MENIMBULKAN ERROR
        namaFolder = splitLokasiFolder[a].replace("-----", "#");

        document.getElementById('allFolder').innerHTML += "<span style=\"text-decoration:underline;\">" +
            splitIdFoldLast[a] + " | " + namaFolder + "</span><br>";
        fileFold = "<?php echo $userIdSelectGet; ?>-" + splitIdFoldLast[a] + "-" + getDateTime();
        document.getElementById('allFolder').innerHTML += daftarFolder(namaFolder, 2, a, splitIdFoldLast[a], fileFold);

        allIdFoldLast += splitIdFoldLast[a] + ", ";

    }

    setTimeout(function() {
        addEvents(a);
    }, 500);

    document.getElementById('allIdFoldLast').value = allIdFoldLast;
}

function daftarFolder(lokasiFolder, level, urutan, idFoldLast, fileFoldParent) {
    level++;
    var fso = new ActiveXObject("Scripting.FileSystemObject");
    var parentFolder = fso.GetFolder(lokasiFolder); // DAPATKAN FOLDER

    var enumFolders = new Enumerator(parentFolder.SubFolders);
    var jumlahFolder = jmlFolder(lokasiFolder); // BERAPA JUMLAH FOLDER DIDALAM FOLDER YANG DICENTANG
    var jumlahFile = jmlFile(lokasiFolder); // BERAPA JUMLAH FILE DIDALAM FOLDER YANG DICENTANG

    var folderItem = "";

    if (jumlahFolder > 0 || jumlahFile > 0) //JIKA FOLDER YANG DICENTANG ADA ISINYA BERI NAMA ID UL PERTAMA
    { // LEVEL 1 NYA ADALAH LEVEL YANG DIPUNYA OLEH idFoldLast PERTAMA KALI YANG DIAMBIL DARI <php echo $allIdFoldRefGet; >
        if (level == 3) //FOLDER BERADA PALING AWAL SETELAH CEK FOLDER DIPILIH
        {
            idUL = "id=\"LinkedList" + urutan + "\"";
        } else {
            idUL = "";
        }
        folderItem += "<ul " + idUL + ">";
    }
    var pathNameFolder = ""; // NAMA FOLDER TERMASUK LOKASI FOLDER TSB BERADA
    var nameFolder =
        ""; // NAMA FOLDER SAJA TANPA LOKASI FOLDER TSB / AMBIL FOLDER TERAKHIR DARI LOKASIFOLDER YANG DIDAPAT
    var fileFold = "";
    var urutanIdFold = 0;
    var idFold = "";
    var tipeKonten = "";
    var folderParent, hiddenFold;
    var moveFile = "tidak";
    //PERULANGAN UNTUK ISI FOLDER DARI FOLDER YANG DICENTANG
    for (; !enumFolders.atEnd(); enumFolders.moveNext()) {
        // PERULANGAN / BERTAMBAH SESUAI JUMLAH SUBFOLDER UNTUK ANGKA DIBELAKANG TITIK, MISAL 43.1, 43.2, 43.3 (urutanIdFold = 1,2,3 BUKAN 43)
        // IDFOLD TERAKHIR YANG DIDAPAT DARI PROSES REKURSIF DITAMBAHKAN STRING NYA DENGAN urutanIdFold YANG BERTAMBAH KARENA PERULANGAN
        urutanIdFold++;
        idFold = idFoldLast + "." + urutanIdFold;
        pathNameFolder = enumFolders.item();
        if (jmlFolder(pathNameFolder) == 0) {
            tipeKonten = "file";
        } else if (jmlFolder(pathNameFolder) > 0) {
            tipeKonten = "folder";
        }

        fileFold = "<?php echo $userIdSelectGet; ?>-" + idFold + "-" + getDateTime();

        folderItem += "<li id=\"" + idFold + "\" style='cursor:pointer;'>";
        folderItem += "<input type='hidden' style='width:900px;' id='hiddenFold_" + idFold + "' value='" + idFold +
            ":-:" + idFoldLast + ":-:" + level + ":-:<?php echo $userIdSelectGet; ?>:-:" + new String(pathNameFolder)
            .replace("'", "\&#39;") + ":-:" + tipeKonten + ":-:" + fileFold + "'>";
        //folderItem += "<textarea id='taFold_"+idFold+"' style=\"display:none;\">"+idFold+":-:"+idFoldLast+":-:"+level+":-:<?php echo $userIdSelectGet; ?>:-:"+new String(pathNameFolder).replace("'","\'")+":-:"+tipeKonten+"</textarea>";
        folderItem +=
            "<img id=\"imgFold\" src=\"../../picture/folder-horizontal.png\" height=\"17\" onClick=\"\" >&nbsp;&nbsp;";
        folderItem += pathNameFolder;
        // PANGGIL KEMBALI FUNGSI daftarFolder(lokasiFolder) / REKURSIF JIKA DIDALAM FOLDER TERDAPAT FOLDER DST
        folderItem += daftarFolder(pathNameFolder, level, urutan, idFold, fileFold);

        folderItem += "</li>";
    }

    // TAMPILKAN ISI FILE YANG BERADA DIDALAM FOLDER YANG DICENTANG BUKAN DALAM SUBFOLDER
    if (jumlahFolder > 0 || jumlahFile > 0) {
        var fileItem = "";
        //var extDoc = "txt";
        var idFoldRefOtherFile, pathNameFile, namaFile, fileDoc, levelDoc, extDoc;

        var enumFiles = new Enumerator(parentFolder.files);
        var urutanIdDoc = 0;

        //var idDoc = "";
        if (jumlahFolder > 0 && jumlahFile > 0) {
            folderParent = "<?php echo $userIdSelectGet; ?>-" + idFoldLast + "." + (jumlahFolder + 1) + "-" +
                getDateTime(); // NAMA FOLDER DIMANA FILE AKAN DICOPY;
        } else {
            folderParent = fileFoldParent; // NAMA FOLDER DIMANA FILE AKAN DICOPY
        }

        for (; !enumFiles.atEnd(); enumFiles.moveNext()) {
            urutanIdDoc++;
            // PATH FILE DAN NAMA FILE BESERTA EXTENSION
            pathNameFile = enumFiles.item();
            // REPLACE EXTENSION FILE DENGAN "" (KOSONG) DI NAMA FILE, HAL INI UNTUK MENGAMBIL NAMA FILE NYA SAJA TANPA EXTENSION
            pathDoc = new String(pathNameFile).replace(ShowFileName(new String(pathNameFile)), "");
            // NAMA FILE SAJA TANPA EXTENSION

            // CARA DIBAWAH INI BISA REMOVE EXTENSION NAMUN NAMA FILE YANG MENGANDUNG NAMA EXTENSION DI TENGAH2 JUGA IKUT KE REMOVE
            //namaFile = ShowFileName(pathNameFile).replace("."+ShowFileName(pathNameFile).split('.').pop(), "");
            // PERBAIKI DENGAN CARA DIBAWAH YANG MENGHILANGKAN EXTENTION YANG HANYA BERADA DI AKHIR NAMA FILE
            namaFile = ShowFileName(pathNameFile).substr(0, ShowFileName(pathNameFile).lastIndexOf('.'));
            // NAMA FILE DENGAN EXTENSION
            fileDoc = ShowFileName(pathNameFile);
            extDoc = ShowFileName(pathNameFile).split('.').pop();
            levelDoc = (parseInt(level) - 1);

            idDoc = idFoldLast + "." + urutanIdDoc;
            idFoldRefOtherFile = idFoldLast;

            fileDoc = "<?php echo $userIdSelectGet; ?>-" + urutanIdDoc + "-" + idFoldRefOtherFile + "-" +
                getDateTime() + "-<?php echo $userIdSelectGet; ?>." + extDoc;
            //folderParent = fileFoldParent; // NAMA FOLDER DIMANA FILE AKAN DICOPY

            if (jumlahFolder > 0 && jumlahFile > 0) {
                idFoldRefOtherFile = idFoldLast + "." + parseInt(jumlahFolder + 1);

                pathNameFile = new String(enumFiles.item()).replace(fileDoc, "\All Other File\\" + fileDoc);
                //pathDoc = new String(pathNameFile).replace(ShowFileName(new String(pathNameFile)), "");
                pathDoc = pathNameFile.replace(fileDoc, "");
                moveFile = "iya";

                levelDoc = parseInt(level);
                //folderParent = "<?php echo $userIdSelectGet; ?>-"+idFoldLast+"."+(jumlahFolder+1)+"-"+getDateTime(); // NAMA FOLDER DIMANA FILE AKAN DICOPY;
            }

            if (ShowFileName(pathNameFile).split('.').length == 1) {
                ajaxConvertFold(extDoc + ":-:" + new String(pathDoc.replace(extDoc, "")).replace("'", "\&#39;"),
                    "gagalUploadTanpaExt", "simpanFolderFile");
            }
            if (ShowFileName(pathNameFile).split('.').length > 1) {
                fileItem += "<li id=\"" + idDoc + "\">";
                fileItem += "<input type='hidden' style='width:900px;' id='hiddenFile_" + idDoc + "' value='" +
                    urutanIdDoc + ":-:" + idFoldRefOtherFile + ":-:" + new String(namaFile).replace("'", "\&#39;") +
                    ":-:" + fileDoc + ":-:" + extDoc + ":-:<?php echo $userIdSelectGet; ?>:-:" + new String(pathDoc)
                    .replace("'", "\&#39;") + ":-:" + moveFile + ":-:" + levelDoc + ":-:" + folderParent + "'>";
                fileItem += "<img src=\"../../../picture/document.png\" height=\"17\"/>&nbsp;&nbsp;";
                fileItem += pathNameFile;
                fileItem += "</li>";
            }
        }

        folderItem += fileItem;

        folderItem += "</ul>";
    }

    if (jumlahFolder == 0 && jumlahFile == 0) {
        folderItem += "<ul id=\"LinkedList" + urutan + "\"></ul>";
    }

    return folderItem;
}

function beriNilaiAllIdFold() {
    var allIdFold, idFold2, liIdFold, splitHiddenValue, nmFold2;
    var splitIdFoldLast = "<?php echo $allIdFoldRefGet; ?>".split("-");
    var pjgSplit = (splitIdFoldLast.length - 1);

    for (var a = 0; a < pjgSplit; a++) {
        idFold1 = splitIdFoldLast[a];
        allIdFold += idFold1 + ", ";
    }

    var liElements = document.getElementsByTagName('li');
    for (var i = 0; i < liElements.length; i++) // LAKUKAN PERULANGAN SEJUMLAH LI YANG DIDAAPAT BAIK FOLDER ATAU FILE
    {
        liIdFold = "hiddenFold_" + liElements[i].id; // ID DARI HIIDEN FILE UNTUK LI YANG ISINYA FOLDER
        //liIdFile = "hiddenFile_"+liElements[i].id; // ID DARI HIIDEN FILE UNTUK LI YANG ISINYA FILE

        if (document.getElementById(liIdFold) != null) {
            splitHiddenValue = document.getElementById(liIdFold).value.split(":-:");
            idFold2 = splitHiddenValue[0];
            nmFold2 = splitHiddenValue[4];
            allIdFold += idFold2 + " " + nmFold2 + ", ";
        }
    }

    document.getElementById('allIdFold').value = allIdFold;
}

function klikBtnBuatFolder() {
    // LAKUKAN PEMISAHAN DENGAN DELIMETER (*****) PADA $allCekFolderGet YANG BERISI NAMA FOLDER DAN PATH
    var splitLokasiFolder = "<?php echo str_replace("\\", "\\\\" ,$allCekFolderGet); ?>".split("*****");
    // LAKUKAN PEMISAHAN DENGAN DELIMETER (-) PADA $allIdFoldRefGet YANG BERISI IDFOLDREF
    var splitIdFoldLast = "<?php echo $allIdFoldRefGet; ?>".split(
        "-"); // JUMLAH TOTAL IDFOLDREF YANG DIDAPAT SETELAH DI PISAH
    var pjgSplit = (splitIdFoldLast.length - 1);

    var allIdFoldLast, idFold1, namaFolder1, fileFold1, tipeKonten1, idFoldRefOther1, idFoldOther1, namaFolderOther1,
        fileFoldOther1;
    var idFoldRef1 = "<?php echo $idFoldRefGet; ?>";

    var jumlahFolder1 = 0;
    var jumlahFile1 = 0;
    for (var a = 0; a < pjgSplit; a++) {
        // REPLACE NAMA FOLDER DAN PATH YANG MENGANDUNG "#" DENGAN "-----" KARENA NAMA FOLDER DENGAN "#" AKAN MENIMBULKAN ERROR
        idFold1 = splitIdFoldLast[a];
        //namaFolder1 = splitLokasiFolder[a].replace("-----", "#");
        namaFolder1 = splitLokasiFolder[a];
        fileFold1 = "<?php echo $userIdSelectGet; ?>-" + idFold1 + "-" + getDateTime();

        jumlahFolder1 = jmlFolder(namaFolder1);
        jumlahFile1 = jmlFile(namaFolder1);
        //alert(namaFolder1+" | "+jumlahFolder2+" | "+jumlahFile2);
        if (jumlahFolder1 == 0) //JIKA DIDALAM FOLDER TERDAPAT FILE SAJA MAKA TIPEKONTEN = FILE
        {
            tipeKonten1 = "file";

            var liElements = document.getElementsByTagName('li'); // BUAT VARIABEL UNTUK SEMUA TAG LI YANG ADA DI PAGE
            for (var i = 0; i < liElements
                .length; i++) // LAKUKAN PERULANGAN SEJUMLAH LI YANG DIDAAPAT BAIK FOLDER ATAU FILE
            {
                liIdFile = "hiddenFile_" + liElements[i].id; // ID DARI HIIDEN FILE UNTUK LI YANG ISINYA FILE
                if (document.getElementById(liIdFile) !== null) {
                    splitHiddenFile = document.getElementById(liIdFile).value.split(":-:");
                    fileFold1 = splitHiddenFile[9];
                    //alert(idFold1+":-:"+idFoldRef1+":-:2:-:<?php echo $userIdSelectGet; ?>:-:"+new String(namaFolder1)+":-:"+tipeKonten1+":-:"+fileFold1);
                    ajaxConvertFold(idFold1 + ":-:" + idFoldRef1 + ":-:2:-:<?php echo $userIdSelectGet; ?>:-:" +
                        new String(namaFolder1) + ":-:" + tipeKonten1 + ":-:" + fileFold1, "simpanFolderUserData",
                        "simpanFolderFile");
                    createLevelFold("2", fileFold1);
                }
            }
        } else if (jumlahFolder1 > 0) // JIKA DIDALAM FOLDER TERDAPAT FOLDER MAUPUN FILE MAKA TIPEKONTEN = FOLDER
        {
            tipeKonten1 = "folder";

            // >>>>> SIMPAN DATABASE
            ajaxConvertFold(idFold1 + ":-:" + idFoldRef1 + ":-:2:-:<?php echo $userIdSelectGet; ?>:-:" + new String(
                    namaFolder1) + ":-:" + tipeKonten1 + ":-:" + fileFold1, "simpanFolderUserData",
                "simpanFolderFile");
            createLevelFold("2", fileFold1);
        }

        if (jumlahFolder1 > 0 && jumlahFile1 >
            0
        ) // BUAT FOLDER DENGAN NAMA "ALL OTHER FILE" UNTUK DIISI FILE2, YANG MANA UNTUK FOLDER YANG DIDALAMNYA TERDAPAT FOLDER MAUPUN FILE 
        {
            idFoldOther1 = splitIdFoldLast[a] + "." + (parseInt(jumlahFolder1) + 1);
            idFoldRefOther1 = splitIdFoldLast[a];
            namaFolderOther1 = namaFolder1;

            fileFoldOther1 = "<?php echo $userIdSelectGet; ?>-" + idFoldOther1 + "-" + getDateTime();

            // >>>>> SIMPAN DATABASE
            /*ajaxConvertFold(idFoldOther1+":-:"+idFoldRefOther1+":-:3:-:<?php echo $userIdSelectGet; ?>:-:"+new String(namaFolderOther1)+"\\All Other File:-:file:-:"+fileFoldOther1, "simpanFolderUserData", "simpanFolderFile");
            createLevelFold("3", fileFoldOther1);*/
        }
    }

    var outputString = "";
    var liElements = document.getElementsByTagName('li'); // BUAT VARIABEL UNTUK SEMUA TAG LI YANG ADA DI PAGE
    var liIdFold, splitHiddenValue, idFoldOther2, idFoldRefOther2, namaFolderOther2, fileFoldOther2, idFold2,
        idFoldRef2, foldSubOther2, namaFolder2, fileFold2, tipeKonten2;

    var foldSub2 = 0; // ATAU LEVEL
    var jumlahFolder2 = 0;
    var jumlahFile2 = 0;
    for (var i = 0; i < liElements.length; i++) // LAKUKAN PERULANGAN SEJUMLAH LI YANG DIDAAPAT BAIK FOLDER ATAU FILE
    {
        liIdFold = "hiddenFold_" + liElements[i].id; // ID DARI HIIDEN FILE UNTUK LI YANG ISINYA FOLDER
        liIdFile = "hiddenFile_" + liElements[i].id; // ID DARI HIIDEN FILE UNTUK LI YANG ISINYA FILE

        if (document.getElementById(liIdFold) != null) {
            // AMBIL VALUE HIDDEN TYPE YANG MEMPUNYAI ID "hiddenId_"(DITAMBAH ID LI YANG DIDAPAT)
            //outputString += document.getElementById(liIdFold).value + "<br />";
            // >>>>> SIMPAN DATABASE

            splitHiddenValue = document.getElementById(liIdFold).value.split(":-:");

            namaFolder2 = splitHiddenValue[4];
            fileFold2 = splitHiddenValue[6];
            jumlahFolder2 = jmlFolder(namaFolder2);
            jumlahFile2 = jmlFile(namaFolder2);

            ajaxConvertFold(document.getElementById(liIdFold).value, "simpanFolderUserData", "simpanFolderFile");
            //ajaxConvertFold(idFold2+":-:"+idFoldRef2+":-:"+foldSub2+":-:<?php echo $userIdSelectGet; ?>:-:"+new String(namaFolder2)+":-:"+fileFold2+":-:"+tipeKonten2, "simpanFolderUserData", "simpanFolderFile");
            createLevelFold(splitHiddenValue[2], fileFold2);

            //outputString += "("+jumlahFolder2+") ("+jumlahFile2+") "+document.getElementById(liIdFold).value + "<br />";

            if (jumlahFolder2 > 0 && jumlahFile2 >
                0
            ) // BUAT FOLDER DENGAN NAMA "ALL OTHER FILE" UNTUK DIISI FILE2, YANG MANA UNTUK FOLDER YANG DIDALAMNYA TERDAPAT FOLDER MAUPUN FILE 
            {
                idFoldRefOther2 = splitHiddenValue[0];
                foldSubOther2 = parseInt(splitHiddenValue[2]) + 1;
                idFoldOther2 = idFoldRefOther2 + "." + (parseInt(jumlahFolder2) + 1);
                namaFolderOther2 = namaFolder2;


                fileFoldOther2 = "<?php echo $userIdSelectGet; ?>-" + idFoldOther2 + "-" + getDateTime();

                // >>>>> SIMPAN DATABASE
                //folderItem += "<textarea id='taFold_"+idFold+"' style=\"display:none;\">"+idFold+":-:"+idFoldLast+":-:"+level+":-:<?php echo $userIdSelectGet; ?>:-:"+new String(pathNameFolder).replace("'","\'")+":-:"+tipeKonten+"</textarea>";

                /*ajaxConvertFold(idFoldOther2+":-:"+idFoldRefOther2+":-:"+foldSubOther2+":-:<?php echo $userIdSelectGet; ?>:-:"+new String(namaFolderOther2)+"\\All Other File:-:file:-:"+fileFoldOther2, "simpanFolderUserData", "simpanFolderFile");
                createLevelFold((foldSubOther2), fileFoldOther2);*/
            }
        }
        // JIKA SUDAH DISIMPAN SEMUA MAKA MUNCULKAN STATUS SELESAI TRANSFER FOLDER
        if (i == (liElements.length - 1)) {
            ajaxConvertFold("", "finishFolderUserData", "simpanFolderFile");
        }
    }
}

function klikBtnBuatFile() {
    var outputString, splitHiddenValue;
    var liElements = document.getElementsByTagName('li'); // BUAT VARIABEL UNTUK SEMUA TAG LI YANG ADA DI PAGE
    var liIdFile, splitTaFold, fileName, oldFileName, pathAsal, pjgFileName, moveFile, extFile, levelFold, pathAsal,
        fileAsal, folderTujuan, FileTujuan, pathTujuan;
    var idFoldOther, idFoldRefOther, foldSubOther, pathOther, fileFoldOther;
    //alert(liElements.length);
    for (var i = 0; i < liElements.length; i++) // LAKUKAN PERULANGAN SEJUMLAH LI YANG DIDAAPAT BAIK FOLDER ATAU FILE
    {
        liIdFile = "hiddenFile_" + liElements[i].id; // ID DARI HIIDEN FILE UNTUK LI YANG ISINYA FILE
        if (document.getElementById(liIdFile) !== null) {
            splitTaFold = document.getElementById(liIdFile).value.split(":-:");

            fileAsal = splitTaFold[2] + "." + splitTaFold[4];
            fileTujuan = splitTaFold[3];
            //extFile = splitTaFold[4];
            pathAsal = splitTaFold[6];
            levelFold = splitTaFold[8];
            folderTujuan = splitTaFold[9];

            if (splitTaFold[4] != "") //JIKA EXTENSION FILE TIDAK KOSONG
            {
                ajaxConvertFold(document.getElementById(liIdFile).value, "simpanFileUserData", "simpanFolderFile");

                if (splitTaFold[7] ==
                    "iya"
                ) // splitTaFold[7] = moveFile (SALAH SATU PARAM DARI "document.getElementById(liIdFile).value")
                {
                    /*fileItem += "<input type='text' style='width:900px;' id='hiddenFile_"+idDoc+"' value='"+urutanIdDoc+":-:"+idFoldRefOtherFile+":-:"+new String(namaFile).replace("'","\&#39;")+":-:"+fileDoc+":-:"+extDoc+":-:<?php echo $userIdSelectGet; ?>:-:"+new String(pathDoc).replace("'","\&#39;")+":-:"+moveFile+":-:"+levelDoc+":-:"+folderParent+"'>*/

                    idFoldOther = splitTaFold[1];
                    idFoldRefOther = idFoldRefFromIdFold(splitTaFold[1]);
                    foldSubOther = splitTaFold[8];
                    //pathOther = splitTaFold[6];
                    pathAsal = splitTaFold[6].replace(splitTaFold[2] + "." + splitTaFold[4], "");
                    fileFoldOther = splitTaFold[9];

                    //alert(idFoldRefOther+"#####"+idFoldOther+":-:"+idFoldRefOther+":-:"+foldSubOther+":-:<?php echo $userIdSelectGet; ?>:-:"+new String(pathAsal)+"\\All Other File:-:file:-:"+fileFoldOther+" ----------- simpanFolderUserData, simpanFolderFile");
                    ajaxConvertFold(idFoldOther + ":-:" + idFoldRefOther + ":-:" + foldSubOther +
                        ":-:<?php echo $userIdSelectGet; ?>:-:" + new String(pathAsal) +
                        "\\All Other File:-:file:-:" + fileFoldOther, "simpanFolderUserData", "simpanFolderFile");
                    createLevelFold(foldSubOther, fileFoldOther);
                }
                /*alert(splitTaFold[6]+" - "+splitTaFold[4]+" | PATH ASAL : "+pathAsal+" \r\n FILE ASAL : "+fileAsal+"  \r\n FOLDER TUJUAN : "+folderTujuan+" \r\n FILE TUJUAN : "+fileTujuan);*/
                kopiFile(levelFold, pathAsal, fileAsal, folderTujuan, fileTujuan);
            }

        }
        if (i == (liElements.length - 1)) // JIKA SUDAH DISIMPAN SEMUA MAKA MUNCULKAN STATUS SELESAI TRANSFER FILE
        {
            ajaxConvertFold("", "finishFileUserData", "simpanFolderFile");
        }
    }
}

function idFoldRefFromIdFold(idFold) {
    var idFoldRef = "";
    var splitIdFold = idFold.split(".");
    var pjgSplitIdFold = splitIdFold.length;
    for (var i = 0; i <= (pjgSplitIdFold - 1); i++) {
        if (i == 0) // ANGKA PERTAMA TIDAK DIKASI TITIK, KARENA FORMATNYA ADALAH A.B.C
        {
            idFoldRef += splitIdFold[i];
        } else {
            if (i != (pjgSplitIdFold -
                    1)) // JIKA BUKAN YANG TERAKHIR, ATAU TUJUANYA ADALAH MEMOTONG ANGKA TERAKHIR DIBELAKANG KOMA
            {
                idFoldRef += "." + splitIdFold[i];
            }
        }
    }

    return idFoldRef;
}

function jmlFolder(lokasiFolder) {
    var fso = new ActiveXObject("Scripting.FileSystemObject");
    var parentFolder = fso.GetFolder(lokasiFolder); // DAPATKAN FOLDER
    var enumFolders = new Enumerator(parentFolder.SubFolders);
    var i = 0;
    for (; !enumFolders.atEnd(); enumFolders.moveNext()) {
        i++;
    }

    return i;
}

function jmlFile(lokasiFolder) {
    var fso = new ActiveXObject("Scripting.FileSystemObject");
    var parentFolder = fso.GetFolder(lokasiFolder); // DAPATKAN FOLDER
    var enumFiles = new Enumerator(parentFolder.files);
    var i = 0;
    for (; !enumFiles.atEnd(); enumFiles.moveNext()) {
        i++;
    }

    return i;
}

function getDateTime() {
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var day = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    if (month.toString().length == 1) {
        var month = '0' + month;
    }
    if (day.toString().length == 1) {
        var day = '0' + day;
    }
    if (hour.toString().length == 1) {
        var hour = '0' + hour;
    }
    if (minute.toString().length == 1) {
        var minute = '0' + minute;
    }
    if (second.toString().length == 1) {
        var second = '0' + second;
    }
    var dateTime = year + month + day + '-' + hour + minute + second;
    return dateTime;

}

function ShowFileName(filespec) {
    var fso, s = "";
    fso = new ActiveXObject("Scripting.FileSystemObject");
    s += fso.GetFileName(filespec);
    return (s);
}

function showFolderName(folderSpec) {
    var fso, folder;
    fso = new ActiveXObject("Scripting.FileSystemObject");
    folder = fso.GetFolder(folderSpec);

    return folder.Name;
}

function createLevelFold(levelFold, folderName) {
    var myObject;
    var newFolderLevel = "//10.0.2.7/andhikaportal/archives/data/documentConvFold/LEVEL" + levelFold
    myObject = new ActiveXObject("Scripting.FileSystemObject");
    if (myObject.FolderExists(newFolderLevel)) {
        //alert(newFolderLevel+" sudah ada \n\r "+newFolderLevel+"/"+showFolderName(folderName));
        if (!myObject.FolderExists(newFolderLevel + "/" + folderName)) {
            myObject.CreateFolder(newFolderLevel + "/" + folderName);
        }
    } else if (!myObject.FolderExists(newFolderLevel)) {
        //alert(newFolderLevel+" belum ada \n\r "+newFolderLevel+"/"+showFolderName(folderName));
        myObject.CreateFolder(newFolderLevel);
        myObject.CreateFolder(newFolderLevel + "/" + folderName);
    } else {
        parent.document.getElementById('simpanFolderFile').innerHTML = "GAGAL DUPLIKAT FOLDER : " + folderName;
    }
}

function kopiFile(levelFold, pathAsal, fileAsal, folderTujuan, fileTujuan) {
    var fso, fileCopy, filePaste;
    var newFolderLevel = "//10.0.2.7/andhikaportal/archives/data/documentConvFold/LEVEL" + levelFold;
    fso = new ActiveXObject("Scripting.FileSystemObject");

    fileCopy = pathAsal + fileAsal;
    filePaste = newFolderLevel + "/" + folderTujuan + "/" + fileTujuan;

    if (fso.FolderExists(newFolderLevel + "/" + folderTujuan)) {
        fso.CopyFile(fileCopy, filePaste);
    }
}

function createAnotherFold(folderName) {
    var myObject, newfolder;
    myObject = new ActiveXObject("Scripting.FileSystemObject");
    if (!myObject.FolderExists(folderName)) {
        myObject.CreateFolder(folderName);
    } else {
        parent.document.getElementById('simpanFolderFile').innerHTML = "GAGAL DUPLIKAT FOLDER : " + folderName;
    }
}

function kopiAnotherFile(paramAjax, oldFileName, fileName, pathDoc) {
    //alert(oldFileName+", "+fileName+", "+pathDoc);
    var fso, getPathNameFile, getPathNameFile2, fileCopy, filePaste, filePaste2;
    //alert("OLD :"+oldFileName+", COPY :"+fileCopy+", PASTE :"+filePaste);
    var fso = new ActiveXObject("Scripting.FileSystemObject");

    // JIKA PARAM oldFileName TIDAK KOSONG, ARTINYA FILE INI AKAN DIRENAME DULU BARU DIKOPI KE FOLDER "All Other File"
    if (oldFileName != "") {
        fileCopy = pathDoc.replace("\\All Other File", "") +
            oldFileName; // FILE YANG AKAN DIKOPI DALAM FOLDER YANG SAMA
        filePaste = pathDoc.replace("\\All Other File", "") + fileName; // FILE YANG SUDAH DIKOPI DALAM FOLDER YANG SAMA
        filePaste2 = pathDoc + fileName // FILE (DENGAN NAMA BARU) YANG AKAN DIKOPI KE DALAM FOLDER "All Other File"

        if (fso.FileExists(fileCopy)) {
            // SIMPAN DATABASE
            ajaxConvertFold(paramAjax, "simpanFileUserData", "simpanFolderFile");
            // RENAME FILE	
            getPathNameFile = fso.GetFile(fileCopy); // FILE DENGAN MASIH NAMA LAMA YANG AKAN DIKOPI 
            getPathNameFile.Move(
                filePaste); // PASTE FILE DENGAN NAMA BARU DI FOLDER YANG SAMA (ARTINYA FILE SUDAH DIRENAME)
            // KOPI FILE
            getPathNameFile2 = fso.GetFile(filePaste); // FILE DENGAN BARU YANG AKAN DIKOPI 
            getPathNameFile2.Move(filePaste2); // PASTE FILE DENGAN NAMA BARU KE DALAM FOLDER "All Other File"
        } else {
            parent.document.getElementById('simpanFolderFile').innerHTML = "GAGAL KOPI FILE : " + pathDoc + fileName;
        }
    }
    if (oldFileName ==
        "") // JIKA PARAM oldFileName KOSONG, ARTINYA FILE INI AKAN LANGSUNG DIKOPI KE FOLDER "All Other File"
    {
        fileCopy = pathDoc.replace("\\All Other File", "") + fileName; // FILE YANG AKAN DIKOPI DALAM FOLDER YANG SAMA
        filePaste = pathDoc + fileName; // FILE YANG AKAN DIKOPI KE DALAM FOLDER "All Other File"

        if (fso.FileExists(fileCopy)) {
            // SIMPAN DATABASE
            ajaxConvertFold(paramAjax, "simpanFileUserData", "simpanFolderFile");
            // KOPI FILE
            getPathNameFile = fso.GetFile(fileCopy); // FILE YANG AKAN DIKOPI 
            getPathNameFile.Move(filePaste); // PASTE FILE KE DALAM FOLDER "All Other File"
        } else {
            parent.document.getElementById('simpanFolderFile').innerHTML = "GAGAL KOPI FILE : " + pathDoc + fileName;
        }
    }
}

function ajaxConvertFold(id, aksi, halaman) {
    var mypostrequest = new ajaxRequest()
    mypostrequest.onreadystatechange = function() {
        if (mypostrequest.readyState == 4) {
            if (mypostrequest.status == 200 || window.location.href.indexOf("http") == -1) {
                parent.document.getElementById(halaman).innerHTML = mypostrequest.responseText;
            }
        }
    }

    if (aksi == "simpanFolderUserData") {
        var parameters = "halaman=" + aksi + "&allNilai=" + encodeURIComponent(id).replace(/%5C/g, '\\');
    }
    if (aksi == "simpanOtherFolderUserData") {
        var parameters = "halaman=" + aksi + "&allNilai=" + encodeURIComponent(id).replace(/%5C/g, '\\');
    }
    if (aksi == "simpanFileUserData") {
        var parameters = "halaman=" + aksi + "&allNilai=" + encodeURIComponent(id).replace(/%5C/g, '\\');
    }
    if (aksi == "finishFileUserData") {
        var parameters = "halaman=" + aksi;
    }
    if (aksi == "finishFolderUserData") {
        var parameters = "halaman=" + aksi;
    }
    if (aksi == "gagalUploadTanpaExt") {
        var parameters = "halaman=" + aksi + "&allNilai=" + encodeURIComponent(id).replace(/%5C/g, '\\');;
    }

    mypostrequest.open("POST", "../halPostSetting.php", true);
    mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    mypostrequest.send(parameters);
}

// Add this to the onload event of the BODY element
function addEvents(urutan) {
    //JIKA PEMISAHAN BERDASARKAN ALLIDFOLDREF YANG DISPLIT KEMUDIAN DILOOPING
    for (var a = 0; a < urutan; a++) {
        activateTree(document.getElementById("LinkedList" + a));
    }
}

// This function traverses the list and add links 
// to nested list items
function activateTree(oList) {
    // Collapse the tree
    for (var i = 0; i < oList.getElementsByTagName("ul").length; i++) {
        oList.getElementsByTagName("ul")[i].style.display = "none";
    }
    // Add the click-event handler to the list items
    if (oList.addEventListener) {
        oList.addEventListener("click", toggleBranch, false);
    } else if (oList.attachEvent) { // For IE
        oList.attachEvent("onclick", toggleBranch);
    }
    // Make the nested items look like links
    addLinksToBranches(oList);
}

// This is the click-event handler
function toggleBranch(event) {
    var oBranch, cSubBranches;
    if (event.target) {
        oBranch = event.target;
    } else if (event.srcElement) { // For IE
        oBranch = event.srcElement;
    }
    cSubBranches = oBranch.getElementsByTagName("ul");
    if (cSubBranches.length > 0) {
        if (cSubBranches[0].style.display == "block") {
            cSubBranches[0].style.display = "none";
        } else {
            cSubBranches[0].style.display = "block";
        }
    }
}

// This function makes nested list items look like links
function addLinksToBranches(oList) {
    var cBranches = oList.getElementsByTagName("li");
    var i, n, cSubBranches;
    if (cBranches.length > 0) {
        for (i = 0, n = cBranches.length; i < n; i++) {
            cSubBranches = cBranches[i].getElementsByTagName("ul");
            if (cSubBranches.length > 0) {
                addLinksToBranches(cSubBranches[0]);
                /*cBranches[i].className = "HandCursorStyle";
                cBranches[i].style.color = "blue";
                cSubBranches[0].style.color = "black";
                cSubBranches[0].style.cursor = "auto";*/

                /*cBranches[i].onmouseover = function onmouseover(){
				this.style.background='#DDF0FF';	}
		  cBranches[i].onmouseout = function onmouseout(){
				this.style.background='#FFFFFF';	}
		  cSubBranches[0].onmouseover = function onmouseover(){
				this.style.background='#FFFFFF';	}*/
            }
            cBranches[i].className = "HandCursorStyle";
        }
    }
}

function getElementsStartsWithId(id) {
    var children = document.body.getElementsByTagName('*');
    var elements = [],
        child;
    for (var i = 0, length = children.length; i < length; i++) {
        child = children[i];
        if (child.id.substr(0, id.length) == id)
            elements.push(child);
    }
    return elements;
}

this.window.onload =
    function() {
        setTimeout(function() {
            parent.document.getElementById('errorMsg').innerHTML = "&nbsp;";
            //parent.document.getElementById('simpanFolderFile').innerHTML = "&nbsp;";
            //alert("PROCESS DONE")
        }, 1000);

    }
</script>

<body onLoad="daftarFolderFile('<?php echo str_replace("\\", "\\\\" ,$allCekFolderGet); ?>');">
    <input type="hidden" id="allIdFoldLast">
    <table width="500%">
        <tr>
            <td>
                <div id="allFolder" class="fontMyFolderList">
                </div>

                <div id="tes" class="fontMyFolderList">
                </div>
            </td>
        </tr>
    </table>

</body>