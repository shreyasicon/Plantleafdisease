<?php

if(isset($_POST['submit'])){

    // Get the file details
    $file = $_FILES['fileToUpload'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    // Get the Github repository information
    $githubRepo = 'shreyasicon/Plantleafdisease';
    $githubToken = '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';
    $githubBranch = 'main'; // Or the branch you want to save the file to
    $githubDirectory = 'uploads/'; // The directory in the Github repository to save the file to

    // Check if the file was uploaded without errors
    if($fileError === UPLOAD_ERR_OK){

        // Get the file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Generate a unique file name to avoid overwriting existing files
        $newFileName = uniqid('', true) . '.' . $fileExt;

        // Create the file path to save the file to
        $fileDestination = $githubDirectory . $newFileName;

        // Authenticate with Github using a personal access token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/$githubRepo/contents/$fileDestination");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
            "message" => "Add $newFileName",
            "content" => base64_encode(file_get_contents($fileTmpName)),
            "branch" => $githubBranch,
            
            ),
            "author" => array(
                "name" => "ICON",
                
            ),
            "sha" => ""
        )));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: token $githubToken",
            "User-Agent: My App"
        ));
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Check if the file was saved successfully
        if($httpcode === 200 || $httpcode === 201){

            echo "The file $fileName has been uploaded and saved to Github.";

        } else {

            echo "Sorry, there was an error uploading and saving your file to Github.";

        }

    } else {

        echo "Sorry, there was an error uploading your file.";

    }

}
