<?php
function apply($job_id,$cookie) {
    $url = "https://glints.com/api/v2-alc/v2/jobs/$job_id/applications";
    $data = '{"data":{"resume":"2a12c750c12bb77440f0712632e61dac.pdf","note":"","attachments":[]},"source":"Explore"}';
    $headers = [
        "Host: glints.com",
        "Content-Length: ".strlen($data),
        "sec-ch-ua: \"Not/A)Brand\";v=\"8\", \"Chromium\";v=\"126\", \"Google Chrome\";v=\"126\"",
        "Accept-Language: id",
        "sec-ch-ua-mobile: ?1",
        "User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Mobile Safari/537.36",
        "Content-Type: application/json;charset=UTF-8",
        "Accept: application/json, text/plain, */*",
        "sec-ch-ua-platform: \"Android\"",
        "Origin: https://glints.com",
        "sec-fetch-site: same-origin",
        "sec-fetch-mode: cors",
        "sec-fetch-dest: empty",
        "Referer: https://glints.com/id/opportunities/jobs/it-suport-and-network-internship/$job_id?utm_referrer=explore&traceInfo=eefd0ca4fb5dac0d2c3e440616d35b58",
        "Accept-Encoding: gzip",
        "Cookie: $cookie",
        "Priority: u=1, i"
    ];
    
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    
    curl_close($ch);
    
    return $response;
}

function get_job($searchTerm, $countryCode, $locationIds, $cookies, $limit = 5, $offset = 0, $includeExternalJobs = true, $sortBy = "LATEST") {
    // URL endpoint
    $url= "https://glints.com/api/v2/graphql?op=searchJobs";

    // Data JSON yang akan dikirim
    $data= [

        "operationName" => "searchJobs",

        "variables" => [
            "data" => [
                "SearchTerm" => $searchTerm,
                "CountryCode" => $countryCode,
                "sortBy" => $sortBy,
                "LocationIds" => $locationIds,
                "limit" => $limit,
                "offset" => $offset,
                "includeExternalJobs" => $includeExternalJobs,
                "searchVariant" => "CURRENT"
            ]
        ],
        "query" => "query searchJobs(\$data: JobSearchConditionInput!) {
          searchJobs(data: \$data) {
            jobsInPage {
              id
              title
              workArrangementOption
              status
              createdAt
              updatedAt
              isActivelyHiring
              isHot
              shouldShowSalary
              educationLevel
              type
              fraudReportFlag
              salaryEstimate {
                minAmount
                maxAmount
                CurrencyCode
                __typename
              }
              company {
                ...CompanyFields
                __typename
              }
              citySubDivision {
                id
                name
                __typename
              }
              city {
                ...CityFields
                __typename
              }
              country {
                ...CountryFields
                __typename
              }
              salaries {
                ...SalaryFields
                __typename
              }
              location {
                ...LocationFields
                __typename
              }
              minYearsOfExperience
              maxYearsOfExperience
              source
              type
              hierarchicalJobCategory {
                id
                level
                name
                children {
                  name
                  level
                  id
                  __typename
                }
                parents {
                  id
                  level
                  name
                  __typename
                }
                __typename
              }
              skills {
                skill {
                  id
                  name
                  __typename
                }
                mustHave
                __typename
              }
              traceInfo
              __typename
            }
            numberOfJobsCreatedInLast14Days
            totalJobs
            expInfo
            __typename
          }
        }
        fragment CompanyFields on Company {
          id
          name
          logo
          status
          isVIP
          IndustryId
          industry {
            id
            name
            __typename
          }
          __typename
        }
        fragment CityFields on City {
          id
          name
          __typename
        }
        fragment CountryFields on Country {
          code
          name
          __typename
        }
        fragment SalaryFields on JobSalary {
          id
          salaryType
          salaryMode
          maxAmount
          minAmount
          CurrencyCode
          __typename
        }
        fragment LocationFields on HierarchicalLocation {
          id
          name
          administrativeLevelName
          formattedName
          level
          slug
          parents {
            id
            name
            administrativeLevelName
            formattedName
            level
            slug
            parents {
              level
              formattedName
              slug
              __typename
            }
            __typename
          }
          __typename
        }"
    ];
    // Encode data JSON ke string
    $jsonData = json_encode($data);

    // Header HTTP
    $headers = [
        "Host: glints.com",
        "Content-Type: application/json",
        "Content-Length: " . strlen($jsonData),
        "X-Glints-Country-Code: ID",
        "User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Mobile Safari/537.36",
        "Referer: https://glints.com/id/opportunities/jobs/explore?keyword=IT+Support&country=ID&locationName=Jabodetabek&sortBy=LATEST",
        "Origin: https://glints.com",
        "Accept-Language: id",
        "Accept: */*",
        "Cookie: $cookies"
    ];

    // Inisiasi cURL
    $ch = curl_init();

    // Set opsi cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Eksekusi dan ambil respons
    $response = curl_exec($ch);

    // Cek error cURL
    if ($response === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        // Tampilkan hasil
        return $response;
    }

    // Tutup cURL
    curl_close($ch);
}

// Contoh penggunaan fungsi
$searchTerm = "IT Support";
$countryCode = "ID";
$locationIds = [
    "ea61f4ac-5864-4b2b-a2c8-aa744a2aafea",
    "3d6ac5e8-f12d-40cd-823d-44597402ea3b",
    "078b37b2-e791-4739-958e-c29192e5df3e",
    "e2a615bb-0997-42c8-a018-c4a1768ae01f",
    "af0ed74f-1b51-43cf-a14c-459996e39105",
    "262b7d3c-2c51-4854-a7e8-6635e0657338",
    "fbc373c5-cadc-4dd8-acb3-1e57850e2a08",
    "f5817918-7ce1-436d-9478-c438b3466adb",
    "87c39cfb-0e3c-4edb-a7a0-754fb70cc587",
    "826664b3-1f31-497f-bc8a-a23699b1a531",
    "337f8da8-70d8-4916-9733-1cee8e4902e9",
    "ae3c458e-5947-4833-8f1b-e001ce2fad1d",
    "d305a80e-4891-45a4-8c5b-29c9242f431e"
];
$cookies = "_gcl_au=1.1.1209677701.1719570686; _ga=GA1.1.69529440.1719570687; _tgpc=db660141-27a7-581a-abbc-787702979d2b; _fbp=fb.1.1719570688036.227995318678054553; _tt_enable_cookie=1; _ttp=k69oACI6Qs4TgdTtf6kVC5szwAu; builderSessionId=84ab1a8e00bd4d8eac7bb120f1b28802; _tguatd=eyJzYyI6IihkaXJlY3QpIn0=; _tgidts=eyJzaCI6ImQ0MWQ4Y2Q5OGYwMGIyMDRlOTgwMDk5OGVjZjg0MjdlIiwiY2kiOiI2ZjYyNmJhYy1mZGZkLTUxMDMtOWQ5NC05MDI3NmY5NTlkODEiLCJzaSI6IjFjNzkyZTk5LWRlMDAtNWZhOS04ZDdmLWJjZmYzZDJiMGEzYSJ9; g_state={\"i_l\":0}; session=Fe26.2**08932f72129981700a4b5d06eb07543b359bafc254d80db95155b0635230a2d8*2KqLIIZnx17A-V3uPdgq2Q*fu_f-n4RaPOb9czcJ5eDNr0o2JaMItpAXnmp0-lUJf5M_JQ0HXoQQN2gUTZ0HJJA**1e0c535bff59017dedcb7869207563c95037c7c202de3a09d3652f6760140227*vEQ-EGkhXqzNZxDLI4_6eOfpp9APFnGZnGGqTgf8N8g; sensorsdata2015jssdkcross=%7B%22distinct_id%22%3A%2257cdb1bf-7495-4c03-82c4-74fa1baefa6f%22%2C%22first_id%22%3A%221905e666645123-0ed10394f6f09e8-b457556-438750-1905e666646%22%7D";

// Menjalankan fungsi dan menampilkan hasil
while(true){
$response = get_job($searchTerm, $countryCode, $locationIds, $cookies, $limit = 10, $offset = 0, $includeExternalJobs = true, $sortBy = "LATEST");
$data = json_decode($response, true);
if (isset($data['data']['searchJobs']['jobsInPage'])) {
    $jobs = $data['data']['searchJobs']['jobsInPage'];
    foreach ($jobs as $job) {
        echo "\n";
        $job_id = $job['id'];
        $job_company = $job['company']['name'];
        $job_title = $job['title'] . "\n";
        $apply=apply($job_id,$cookies);
        $apply_content = json_decode($apply,true);
        if(!isset($apply_content['error'])){
          echo "[job]     => $job_company => $job_title => success\n";
        } else if(isset($apply_content['error']['details'][0]) && $apply_content['error']['details'][0] == 'The resource you\'re trying to create already exists.'){
          $error_details=$apply_content['error']['details'][0];
          echo "[job]     => $job_company => $job_title";
          echo "[results] => already exists\n";
        } else {
          echo "[errors] Something went wrong\n";
        }
    }
} else {
    echo "Data pekerjaan tidak ditemukan dalam file JSON.\n";
}

for($i=60;$i > 0;$i--){

  echo "\r                                         \r";

  echo "[loading] => waiting for $i seconds";
  sleep(1);
  if($i==1){
    echo "\r                                         \r";
  }
}
}