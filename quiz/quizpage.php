<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="quizpage.css">
    <link rel="stylesheet" href="../navigator.css">
    <title>Quiz</title>
</head>

<body class="bg-gray-50">
    <?php include '../navigator.php'; ?>

    <?php
    # 본인 sql 서버에 맞게 수정하기 #
    $host = "localhost";
    $user = "root";
    $pass = "qkrwnsdyd0416";
    $db = "toka";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("연결실패: " . $conn->connect_error);
    }
    

    // 카테고리 id 값이 같은 word 데이터들을 가져오기
    $categoryId = $_GET['category']; // Replace with the desired category_id
    $count = 3;

    // 정답 단어에 대한 쿼리에서 뷰를 사용합니다.
$getCorrectWordListQuery = "SELECT * FROM words WHERE categoryId = $categoryId";
$getCategoryName = "SELECT category_name as name FROM category WHERE identifier = $categoryId";

$categoryName = $conn->query($getCategoryName)->fetch_assoc();
$result = $conn->query($getCorrectWordListQuery);

// 결과를 담을 배열 초기화
$correctWordList = array();
// 쿼리 결과가 있는지 확인
if ($result->num_rows > 0) {
    // 결과를 배열로 변환
    while ($row = $result->fetch_assoc()) {
        $correctWordList[] = $row;
    }
    // 배열 셔플
    shuffle($correctWordList);
} else {
    echo "0 results";
}

// 오답 단어에 대한 쿼리에서 뷰를 사용합니다.
$failWordList = array();
foreach ($correctWordList as &$word) {
    // Get the 'part' value of the current element
    $currentPart = $word['part'];

    // SQL query to retrieve count random data different from the current element
    $randomDataQuery =
        "SELECT * FROM vw_random_data
        WHERE part = '$currentPart'
        LIMIT $count";

    // execute query
    $randomDataResult = $conn->query($randomDataQuery);

    // Convert the result to an array
    $randomDataList = array();
    while ($randomData = $randomDataResult->fetch_assoc()) {
        $randomDataList[] = $randomData;
    }

    // Store sequentially in failWordList
    $failWordList[] = $randomDataList;
}

    $conn->close();
    ?>

<div class="grid grid-cols-1 gap-4" id="wordContainer">
    <?php
    // Assume both arrays have the same length
    $wordLength = count($correctWordList);

    // Variables to access fields
    $columnName = 'korean';

    // Create a button while traversing correctWordList and failWordList simultaneously
    for ($i = 0; $i < $wordLength; $i++) {
        $wordIndexI = $correctWordList[$i];
        $failWordIndexI = $failWordList[$i];

        // Word output part (in a single column)
        echo "<div class=\"text-center wordSet\" id=\"wordSet{$i}\"";
        if ($i > 0) {
            echo " style=\"display: none;\"";
        }
        echo ">";
        echo "<h2 class=\"text-6xl font-semibold mb-2 ml-4\">{$wordIndexI['english']}</h2>";
        echo "</div>";

        // Buttons output part (in a 2-column grid)
        echo "<div class=\"grid grid-cols-2 gap-8 buttonSet\" id=\"buttonSet{$i}\"";
        if ($i > 0) {
            echo " style=\"display: none;\"";
        }
        echo ">";

        // Create an array to contain both the original word button and the fake data button
        $allButtons = array();

        // Create original word button
        $allButtons[] = "<div class=\"card bg-white p-6 rounded-lg shadow-md\">
        <button type=\"button\" class=\"w-full h-full p-4 rounded-md focus:outline-none focus:border-blue-500 border-0 wordButton\" data-is-correct=\"true\">
        {$wordIndexI[$columnName]}
        </button>
        </div>";
    
    // Generate random data button
    foreach ($failWordIndexI as $randomData) {
        $isCorrect = $randomData['word_id'] == $wordIndexI['word_id'] ? 'true' : 'false';
        $allButtons[] = "<div class=\"card bg-white p-6 rounded-lg shadow-md\">
            <button type=\"button\" class=\"w-full h-full p-4 rounded-md focus:outline-none focus:border-blue-500 border-0 wordButton\" data-is-correct=\"$isCorrect\">
            {$randomData[$columnName]}
            </button>
        </div>";
        }

        // Button count + 1 shuffle
        shuffle($allButtons);

        // Print
        foreach ($allButtons as $button) {
            echo $button;
        }

        // Close the grid container for the current set of buttons
        echo "</div>";
    }
    ?>
</div>

<script>
    let correctCount = 0;
    let currentSetIndex = 1;
    const totalSets = <?php echo $wordLength; ?>;

    function resetButtons() {
        // Clear button colors and enable buttons
        document.querySelectorAll('.wordButton').forEach(function (button) {
            button.style.backgroundColor = '';
            button.disabled = false;
        });
    }

    function resetDisplay() {
        // Hide all word sets and button sets
        document.querySelectorAll('.wordSet').forEach(function (element) {
            element.style.display = 'none';
        });

        document.querySelectorAll('.buttonSet').forEach(function (element) {
            element.style.display = 'none';
        });
    }

    document.addEventListener('click', function (event) {
    const target = event.target;

    // Check if the clicked element is a button with the specified class
    if (target.classList.contains('wordButton')) {
        // Determine if the clicked button is correct
        const isCorrect = target.getAttribute('data-is-correct') === 'true';

        // Update button style based on correctness
        target.style.backgroundColor = isCorrect ? 'blue' : 'red';

        // Increment correctCount only if the correct button is clicked
        correctCount += isCorrect ? 1 : 0;

        // Disable all buttons to prevent further clicks
        document.querySelectorAll('.wordButton').forEach(function (button) {
            button.disabled = true;
        });

        // Clear button colors and enable buttons after 3 seconds
        setTimeout(() => {
            resetButtons();

            // Increment currentSetIndex
            currentSetIndex = (currentSetIndex % totalSets) + 1;

            // Update the displayed index and correct count
            document.getElementById('currentSetIndex').textContent = currentSetIndex;
            document.getElementById('correctCountDisplay').textContent = `Correct: ${correctCount}`;

            // Show the next word set and buttons, hide others
            resetDisplay();
            document.getElementById(`wordSet${currentSetIndex}`).style.display = 'block';
            document.getElementById(`buttonSet${currentSetIndex}`).style.display = 'grid';
        }, 3000);
    }
});

    // Initial display setup
    resetDisplay();
    document.getElementById('wordSet1').style.display = 'block';
    document.getElementById('buttonSet1').style.display = 'grid';
</script>
<div class="text-right mt-8">
    <span class="text-lg" id="currentSetIndex">1</span> / <?php echo $wordLength; ?>
    <div class="text-sm text-gray-600 mt-1" id="correctCountDisplay">Correct: 0</div>
</div>
    </div>
</body>

</html>

