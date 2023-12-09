<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="customquiz.css">
    <link rel="stylesheet" href="../navigator.css">
    <title>Custom Quiz</title>
</head>

<body>
    <?php include '../navigator.php'; ?>

    <?php
    // 데이터베이스 연결 설정
    $host = "localhost";
    $user = "root";
    $pass = "root";
    $db = "toka";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("연결실패: " . $conn->connect_error);
    }

    // 세션을 시작합니다.
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // 로그인한 사용자의 user_id를 확인합니다.
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        // 화면에 출력
        echo "현재 로그인된 사용자의 user_id: " . $user_id;
    } else {
        // 로그인되지 않은 사용자 처리를 수행합니다.
        echo "로그인되지 않았습니다.";
    }

    // 사용자의 user_id와 일치하는 custom_id 가져오기
    $sql = "SELECT custom_id FROM customwords WHERE user_id = '1'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 사용자의 custom_id 목록이 있는 경우
        $questions = []; // 퀴즈 데이터를 저장할 배열 초기화
        while ($row = $result->fetch_assoc()) {
            $custom_id = $row['custom_id'];
        
            // 올바른 답 가져오기
            $customWordsSql = "SELECT c_korean, c_english FROM customwords WHERE user_id = '1' AND custom_id = '$custom_id' ORDER BY RAND() LIMIT 1";
            $customWordsResult = $conn->query($customWordsSql);
            $question = [];
        
            if ($customWordRow = $customWordsResult->fetch_assoc()) {
                $question = [
                    'c_english' => $customWordRow['c_english'], 
                    'c_korean' => $customWordRow['c_korean'],
                    'options' => [$customWordRow['c_korean']]
                ];
            }
        
            // 잘못된 답안 가져오기 (올바른 답을 제외하고)
            $wrongAnswersSql = "SELECT c_korean FROM customwords WHERE user_id = '1' AND custom_id != '$custom_id' ORDER BY RAND() LIMIT 3";
            $wrongAnswersResult = $conn->query($wrongAnswersSql);
        
            while ($wrongAnswerRow = $wrongAnswersResult->fetch_assoc()) {
                $question['options'][] = $wrongAnswerRow['c_korean'];
            }
        
            if (!empty($question)) {
                $questions[] = $question;
            }
        }
        // 데이터베이스 연결 종료
        $conn->close();
    }
    ?>
    <script type="text/javascript">
    var questions = <?php echo json_encode($questions); ?>;
    document.getElementById('nextButton').style.display = 'none';
    </script>


    <div id="quizPage" class="quiz-container">
        <div id="quizContent"></div>
        <button id="startButton" onclick="startQuiz()">Start</button>
        <button id="nextButton" onclick="nextQuestion()" style="display: none;">Next question</button>
    </div>

    <div id="resultPage" class="quiz-container">
        <h2>SCORE <span id="score">0</span></h2>
        <button id="restartButton" onclick="restartQuiz()" style="display: none;">Retry</button>
    </div>



    <script>
    // JavaScript 코드
    var currentQuestion = 0;
    var score = 0;

    function startQuiz() {
        document.getElementById('startButton').style.display = 'none';
        document.getElementById('quizPage').style.display = 'block';
        // 퀴즈 시작 시 `nextButton` 표시
        document.getElementById('nextButton').style.display = 'block';
        displayQuestion();
    }

    function displayQuestion() {
        var question = questions[currentQuestion];
        if (question) {
            var options = getShuffledOptions(question);
            var quizContent = "<h2>" + question.c_english + "</h2>";
            for (var i = 0; i < options.length; i++) {
                quizContent += "<label class='quiz-option'><input type='radio' name='answer' value='" + options[i] +
                    "'>" + options[i] + "</label><br>";
            }
            document.getElementById('quizContent').innerHTML = quizContent;
        } else {
            showResult();
        }
    }

    function getShuffledOptions(question) {
        // 질문에서 옵션 배열 가져오기
        var options = question.options;

        // 옵션 배열 섞기
        shuffleArray(options);

        return options;
    }

    function shuffleArray(array) {
        for (var i = array.length - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));
            var temp = array[i];
            array[i] = array[j];
            array[j] = temp;
        }
    }

    function nextQuestion() {
        var selectedAnswer = document.querySelector('input[name="answer"]:checked');
        if (selectedAnswer) {
            if (selectedAnswer.value === questions[currentQuestion].c_korean) {
                score += 10;
            }
            currentQuestion++;
            displayQuestion();
        } else {
            alert('답을 선택해주세요.');
        }
    }

    function showResult() {
        document.getElementById('quizPage').style.display = 'none';
        document.getElementById('resultPage').style.display = 'block';
        document.getElementById('score').innerText = score;

        // "퀴즈 다시하기" 버튼을 표시
        document.getElementById('restartButton').style.display = 'block';
    }

    function restartQuiz() {
        currentQuestion = 0;
        score = 0;

        document.getElementById('quizPage').style.display = 'block';
        document.getElementById('resultPage').style.display = 'none';

        // "퀴즈 다시하기" 버튼을 다시 숨김
        document.getElementById('restartButton').style.display = 'none';

        displayQuestion();
    }
    </script>
</body>

</html>