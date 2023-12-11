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
    <!-- 네비게이션 바 -->
    <?php include '../navigator.php'; ?>

    <!-- 정상적으로 로그인하여 접속했을 때 -->
    <?php
      if ( $jb_login ) {

    ?>

    <?php
        $host = "localhost";
        $user = "root";
        $pass = "root";
        $db = "toka";

        $conn = new mysqli($host, $user, $pass, $db);

        if ($conn->connect_error) {
            die("연결실패: " . $conn->connect_error);
        }

        // 사용자의 user_id와 일치하는 custom_id 가져오기
        $sql = "SELECT custom_id FROM customwords WHERE user_id = '$identifier'";
        $result = $conn->query($sql);
        $customCount = $result->num_rows;


        if ($result->num_rows > 0) {
            // 사용자의 custom_id 목록이 있는 경우
            $questions = []; // 퀴즈 데이터를 저장할 배열 초기화
            while ($row = $result->fetch_assoc()) {
                $custom_id = $row['custom_id'];

                $sqlQuiz = "SELECT 
                    cw.custom_id, cw.c_korean, cw.c_english,
                    (
                        SELECT w.korean
                        FROM words AS w
                        WHERE NOT w.english = cw.c_english
                        ORDER BY RAND()
                        LIMIT 1
                    ) AS wrong_answer1,
                    (
                        SELECT w.korean
                        FROM words AS w
                        WHERE NOT w.english = cw.c_english
                        ORDER BY RAND()
                        LIMIT 1
                    ) AS wrong_answer2,
                    (
                        SELECT w.korean
                        FROM words AS w
                        WHERE NOT w.english = cw.c_english
                        ORDER BY RAND()
                        LIMIT 1
                    ) AS wrong_answer3
                FROM customwords AS cw
                WHERE cw.custom_id=$custom_id";

                $resultQuiz = $conn->query($sqlQuiz);

                while ($row = $resultQuiz->fetch_assoc()) {
                    $question = [
                        'custom_id' => $row['custom_id'],
                        'c_english' => $row['c_english'],
                        'c_korean' => $row['c_korean'],
                        'options' => [
                            $row['c_korean'],
                            $row['wrong_answer1'],
                            $row['wrong_answer2'],
                            $row['wrong_answer3']
                        ]
                    ];
            
                    // 옵션을 랜덤하게 섞음
                    shuffle($question['options']);
                }

                if (!empty($question)) {
                    $questions[] = $question;
                }
            }
            

            // 모든 질문이 추가된 후에 $questions 배열의 순서를 랜덤으로 섞음
            shuffle($questions);

            // 데이터베이스 연결 종료
            $conn->close();
            
        }
        ?>

    <!-- 그냥 접속했을 때 -->
    <?php
      } else {
    ?>
    <h1>Invalid Access</h1>
    <?php
      }
    ?>

    <script type="text/javascript">
    var questions = <?php echo json_encode($questions); ?>;
    var customCount = <?php echo $customCount; ?>;
    var userId = <?php echo json_encode($identifier); ?>;
    document.getElementById('nextButton').style.display = 'none';
    </script>


    <!-- 퀴즈 컨테이너 -->
    <div class="quizWrap" id="quizWrap">
        <div id="quizPage" class="quiz-container">
            <!-- 퀴즈 영어 단어 -->
            <div id="quizContent"></div>
            <!-- 퀴즈 시작 버튼 -->
            <button id="startButton" onclick="startQuiz()">Start</button>
            <!-- 다음 퀴즈 버튼 -->
            <button id="nextButton" onclick="nextQuestion()" style="display: none;" disabled>Check & Go Next</button>

            <!-- 점수 & 진행상황 -->
            <div id="progress" style="display: none;">
                <span id="proWrap1">
                    <span id="scoreText">Score: </span>
                    <span id="currentScore">0</span>
                </span>

                <span id="proWrap2">
                    <span id="currentNum">1</span>
                    <span id="dash"> / </span>
                    <span id="customCount"><?php echo $customCount; ?></span>
                </span>
            </div>
        </div>
    </div>

    <!-- 결과 페이지 -->
    <div class="resultWrap" id="resultWrap" style="display: none;">
        <div id="resultPage" class="quiz-container">
            <h2 id="sub">SCORE</h2>
            <div id="score">0</div>

            <button id="restartButton" onclick="restartQuiz()">Retry</button>

        </div>
    </div>


    <script>
    // JavaScript 코드
    var currentQuestion = 0;
    var score = 0;
    var incorrect_id = [];

    //퀴즈 시작
    function startQuiz() {
        document.getElementById('startButton').style.display = 'none';
        document.getElementById('quizPage').style.display = 'block';
        document.getElementById('quizPage').style.backgroundColor = 'white';
        document.getElementById('quizPage').style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';

        // 퀴즈 시작 시 `nextButton` 표시
        document.getElementById('nextButton').style.display = 'block';
        // 점수, 진행상황 표시
        document.getElementById('progress').style.display = 'flex';

        //퀴즈 보여주기
        displayQuestion();
    }

    //퀴즈 보여주기
    function displayQuestion() {
        document.getElementById('currentNum').innerText = currentQuestion + 1;

        var question = questions[currentQuestion];
        if (question) {
            var options = getShuffledOptions(question);
            var quizContent = "<h2>" + question.c_english + "</h2>";
            for (var i = 0; i < options.length; i++) {
                quizContent +=
                    "<label class='quiz-option' onclick='handleLabelClick(this)'><input type='radio' name='answer' value='" +
                    options[i] +
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

    //선택된 옵션에 css 주려고 class 추가하는 거//
    function handleLabelClick(label) {
        // 이 함수에서 원하는 동작 수행
        console.log("Label clicked:", label);

        // 모든 라벨에 대한 선택 제거
        document.querySelectorAll('.quiz-option').forEach(function(option) {
            option.classList.remove('selected');
        });

        // 클릭된 라벨에 대한 선택 표시
        label.classList.add('selected');

        // 선택된 클래스가 추가되었을 때만 "Next question" 버튼을 활성화
        var nextButton = document.getElementById('nextButton');
        nextButton.disabled = !label.classList.contains('selected');
    }

    function updateIsCorrect(custom_id, isCorrect) {
        // PHP 스크립트를 호출하여 isCorrect 값을 업데이트
        var scriptUrl = isCorrect ? 'correctcustom.php' : 'incorrectcustom.php';
        $.post(scriptUrl, {
            custom_id: custom_id,
            isCorrect: isCorrect ? 1 : 0 // isCorrect 값을 업데이트
        }, function(data) {
            console.log(data);
        });
    }

    function nextQuestion() {
        var nextButton = document.getElementById('nextButton');
        nextButton.disabled = true;

        var selectedAnswer = document.querySelector('input[name="answer"]:checked');
        var selectedQuizOption = document.querySelector('.quiz-option.selected');

        if (selectedAnswer) {
            var custom_id_to_update = questions[currentQuestion].custom_id;
            if (selectedAnswer.value === questions[currentQuestion].c_korean) {
                score += 10;
                selectedQuizOption.style.backgroundColor = '#00DF66';
                updateIsCorrect(custom_id_to_update, true); // 맞았을 때 isCorrect를 1로 설정
            } else {
                if (!incorrect_id.includes(custom_id_to_update)) {
                    incorrect_id.push(custom_id_to_update);
                }
                selectedQuizOption.style.backgroundColor = '#FF6969';
                updateIsCorrect(custom_id_to_update, false); // 틀렸을 때 isCorrect를 0으로 설정
            }

            document.getElementById('currentScore').innerText = score;

            setTimeout(function() {
                currentQuestion++;
                displayQuestion();
            }, 1000);
        } else {
            alert('Please select an answer.');
        }
    }


    function showResult() {
        document.getElementById('quizWrap').style.display = 'none';
        document.getElementById('resultWrap').style.display = 'flex';
        document.getElementById('resultPage').style.display = 'flex';
        document.getElementById('score').innerText = score;

        // "Retry" 버튼을 표시
        document.getElementById('restartButton').style.display = 'block';

        //틀린 custom_id 저장
        console.log(incorrect_id);
        var incorrectString = incorrect_id.join(',');

        // archives 테이블에 기록 추가
        recordQuestCompletion(userId, 2, 1);

        var check = document.getElementById('customCount').innerText;
        var maxScore = check * 10;

        if (score === maxScore) {
            recordQuestCompletion(userId, 3, 1); // 여기서 user_id, quest_id, isCompleted를 전달
        }

    }

    function restartQuiz() {
        currentQuestion = 0;
        score = 0;
        incorrect_id = [];
        document.getElementById('currentScore').innerText = score;

        document.getElementById('quizWrap').style.display = 'flex';
        document.getElementById('resultWrap').style.display = 'none';

        // "퀴즈 다시하기" 버튼을 다시 숨김
        document.getElementById('restartButton').style.display = 'none';
        document.getElementById('saveBtn').style.display = 'none';

        displayQuestion();
    }

    function saveIncorrect() {
        //틀린 custom_id 저장
        console.log(incorrect_id);
        var incorrectString = incorrect_id.join(',');

        window.location.href = 'incorrectcustom.php?id=' + incorrectString;
    }

    function recordQuestCompletion(userId, questId, isCompleted) {
        $.ajax({
            url: '../quest/questSuccess.php',
            type: 'POST',
            data: {
                user_id: userId, // 동적으로 설정된 사용자의 ID
                quest_id: questId, // 동적으로 설정된 퀘스트의 ID
                isCompleted: isCompleted // 동적으로 설정된 완료 상태
            },
            success: function(responseText) {
                var response = JSON.parse(responseText); // Parse the JSON string
                // 서버 응답에 따라 alert를 표시
                if (response && response.questTitle) {
                    alert(response.questTitle + "를 성공하셨습니다!");
                } else {
                    // 서버 응답에 퀘스트 제목이 없는 경우
                    alert("퀘스트 완료!");
                }
                console.log("Quest completion response: ", response);
            },
            error: function(xhr, status, error) {
                console.error("Quest completion record 실패: ", error);
            }
        });
    }
    </script>
</body>

</html>