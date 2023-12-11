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
    <link rel="stylesheet" href="quizpage.css">
    <link rel="stylesheet" href="../navigator.css">
    <title>Quiz</title>
</head>

<body>
    <!-- 네비게이션 바 -->
    <?php include 'C:\xampp\htdocs\toka\navigator.php'; ?>

    <!-- 정상적으로 로그인하여 접속했을 때 -->
    <?php
      if ($jb_login ) {
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
        
        // $categoryId = $_GET['category']; // Replace with the desired category_id

        // 최종 결과 저장할 question 배열
        // 내부에는 Map
        // 필요한 Key : c_english, c_korean, options(Array : 정답 한글 1 + 오답 한글 3)
        $questions = [];
        $question = [];
        $categoryId = $_GET['category']; // Replace with the desired category_id
        $count = 3;
        $user_id = $_GET['user_id'];

        $getCorrectWordListQuery = "SELECT * FROM words WHERE categoryId = $categoryId" ;
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

        $customCount = count($correctWordList);
        
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
        
            $temp = [];
            // question의 option 필드에 들어갈 list
            $temp = [
                'custom_id' => $word['word_id'],
                'english' => $word['english'], 
                'korean' => $word['korean'],
                'options' => [$word['korean']]
            ];
            while ($randomData = $randomDataResult->fetch_assoc()) {
                $temp['options'][] = $randomData['korean'];
            }

            $question[] = $temp;
            echo "<script>";
            echo "console.log('currentTemp:', " . json_encode($temp) . ");";
            echo "</script>";
        ?>

    <!-- 그냥 접속했을 때 -->
    <?php
      }} else {
    ?>
    <h1>Invalid Access</h1>
    <?php
      }
    ?>

    <script type="text/javascript">
    var questions = <?php echo json_encode($question); ?>;
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
            <button id="restartButton" onclick="restartQuiz()" style="display: none;">Retry</button>
            <button id="saveBtn" onclick="saveIncorrect()" style="display: none;">Save Incorrect Quizzes to
                Review</button>
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
                var quizContent = "<h2>" + question.english + "</h2>";
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

        function nextQuestion() {
            var nextButton = document.getElementById('nextButton');
            nextButton.disabled = true;

            var selectedAnswer = document.querySelector('input[name="answer"]:checked');
            var selectedQuizOption = document.querySelector('.quiz-option.selected');

            if (selectedAnswer) {
                if (selectedAnswer.value === questions[currentQuestion].korean) {
                    score += 10;

                    selectedQuizOption.style.backgroundColor = '#00DF66';
                } else {
                    //틀린 번호의 custom_id 추가해주기
                    incorrect_id.push(questions[currentQuestion].custom_id);

                    selectedQuizOption.style.backgroundColor = '#FF6969';
                }

                document.getElementById('currentScore').innerText = score;

                //1초 뒤에 수행하는 코드
                setTimeout(function() {
                    currentQuestion++;
                    displayQuestion();
                }, 1000);
            } else {
                alert('답을 선택해주세요.');
            }
        }

        function showResult() {
            document.getElementById('quizWrap').style.display = 'none';
            document.getElementById('resultWrap').style.display = 'flex';
            document.getElementById('resultPage').style.display = 'flex';
            document.getElementById('score').innerText = score;

            // "퀴즈 다시하기" 버튼을 표시
            document.getElementById('restartButton').style.display = 'block';
            document.getElementById('saveBtn').style.display = 'block';

            //틀린 custom_id 저장
            console.log(incorrect_id);
            var incorrectString = incorrect_id.join(',');

            // archives 테이블에 기록 추가
            recordQuestCompletion(userId, 1, 1);

            //window.location.href = 'incorrectcustom.php?id=' + incorrectString;
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
            console.log(incorrect_id);
            var incorrectString = incorrect_id.join(',');

            window.location.href = 'saveIncorrect.php?id=' + incorrectString + '&identifier=' +
                <?php echo $user_id; ?> + '&category_id=' + <?php echo $categoryId; ?> + '&score=' + score;
            //openhome();

            // AJAX를 사용하여 서버에 데이터 전송

            // $.ajax({
            //     type: "POST",
            //     url: "saveIncorrect.php", // PHP 파일 경로
            //     data: { id: incorrectString }, // 전송할 데이터
            //     success: function(response) {
            //         console.log(response); // 서버 응답 확인
            //         // 추가적인 처리나 메시지 표시 등을 할 수 있습니다.
            //     },  
            //     error: function(error) {
            //         console.error("에러 발생: ", error);
            //     }
            // });

            // 페이지를 이동하지 않음
            // window.location.href = 'incorrectcustom.php?id=' + incorrectString;
        }

        function openhome() {
            window.location.href = '../main/home.php';
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