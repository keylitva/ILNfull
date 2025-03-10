<x-layout class="min-h-screen bg-[#080A25]">
    <!-- Fixed Timer Bar (hidden until test starts) -->
    <div id="timer-bar" class="fixed top-4 right-4 z-50 hidden">
      <div class="bg-[#080A25] bg-opacity-80 backdrop-blur-md px-4 py-2 rounded-full shadow-lg">
        <span id="timer" class="text-xl font-semibold text-white"></span>
      </div>
    </div>
  
    <div class="container mx-auto min-h-screen flex flex-col items-center py-10 px-4 space-y-8">
      <!-- Test Title -->
      <div class="text-center">
        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">
          {{ $test->test_name }}
        </h1>
      </div>
  
      <!-- Test Attempt Details -->
      <div class="w-full max-w-3xl bg-white/10 backdrop-blur-sm border border-white/20 p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-100 mb-4 text-center">Test Attempt Information</h2>
        <p class="text-gray-300 mb-2">
          <strong>Attempt Date:</strong> {{ \Carbon\Carbon::parse($test_attempt->attempt_date)->format('F d, Y H:i') }}
        </p>
        <p class="text-gray-300 mb-2">
          <strong>Total Questions:</strong> {{ $questions->count() }}
        </p>
        <p class="text-gray-300 mb-2">
          <strong>Time Limit:</strong> {{ $test->time_limit_minutes }} minutes
        </p>
        <p class="text-gray-300">
          <strong>Important:</strong> Answer all questions and click submit when you're finished.
        </p>
      </div>
  
      <!-- Test Instructions -->
      <div class="w-full max-w-3xl bg-white/10 backdrop-blur-sm border border-white/20 p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-100 mb-4 text-center">Test Instructions</h2>
        <ul class="list-disc list-inside text-gray-300 space-y-2">
          <li>Each question type may require different input methods such as text, dropdowns, or checkboxes.</li>
          <li>Some questions allow multiple answers, others only one.</li>
          <li>You can navigate between questions freely and submit when ready.</li>
        </ul>
      </div>
  
      <!-- Start Test Section -->
      <div id="start-section" class="w-full max-w-3xl bg-white/10 backdrop-blur-sm border border-white/20 p-8 rounded-xl shadow-lg text-center">
        <p id="timer-display" class="text-xl text-gray-100 font-semibold mb-4">
          Time Left: {{ gmdate('H:i:s', $test->time_limit_minutes * 60) }}
        </p>
        <button id="start-test" class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-8 py-3 rounded-lg hover:from-indigo-600 hover:to-purple-600 shadow-lg text-lg font-medium transition-all duration-200">
          Start Test
        </button>
      </div>
  
      <!-- Questions Form (Initially Hidden) -->
      <form action="/submit_test" method="POST" class="w-full max-w-3xl bg-white/10 backdrop-blur-sm border border-white/20 p-8 rounded-xl shadow-lg hidden" id="test-form">
        @csrf
        <input type="hidden" name="test_attempt_token" value="{{ $test_attempt->test_attempt_token }}">
        <input type="hidden" name="cheating" id="cheating" value="">
        <input type="hidden" name="time_taken" id="time_taken" value="">
        <h2 class="text-2xl font-semibold text-gray-100 mb-6 text-center">Test Questions</h2>
  
        @foreach ($questions as $index => $question)
          <div class="mb-8 border-b border-white/20 pb-6">
            <div class="flex justify-center items-center mb-4">
              <span class="text-gray-100 font-bold text-xl">{{ $index + 1 }}</span>
            </div>
  
            @if ($question->question_type === 'fill_in_the_gaps' || $question->question_type === 'gap_fill_with_choices')
              <div class="flex justify-center items-center mb-4">
                <p class="text-gray-300 font-medium text-md">{{ $question->explanation }}</p>
              </div>
              <p class="text-gray-100 font-medium text-lg">
                {!! preg_replace_callback('/\[gap_(\d+)\]/', function ($matches) use ($question) {
                    $gapId = (int) $matches[1];
                    $options = json_decode($question->options, true);
                    
                    // Filter options for current gap
                    $gapOptions = array_filter($options, function ($option) use ($gapId) {
                        return $option['gap_id'] === $gapId;
                    });
                    
                    // Determine input type from the first option
                    $inputType = count($gapOptions) ? $gapOptions[array_key_first($gapOptions)]['input_type'] : 'input';
                
                    if ($inputType === 'dropdown') {
                        $dropdownHtml = '<span class="inline-block relative align-middle mx-1">';
                        $dropdownHtml .= '<select name="answers['.$question->question_id.']['.$gapId.']" class="text-sm font-medium text-gray-700 bg-white/80 border-2 border-gray-200 rounded-lg shadow-sm hover:border-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-300 transition-all duration-200 ease-in-out pl-2 pr-8 py-1 w-auto appearance-none" style="min-width: 120px;" required>';
                        $dropdownHtml .= '<option value="" class="text-gray-400 italic">Select</option>';
                        
                        foreach ($gapOptions as $option) {
                            $dropdownHtml .= '<option value="'.htmlspecialchars($option['option_text']).'" class="text-gray-700 hover:bg-blue-50">'
                                            .htmlspecialchars($option['option_text']).'</option>';
                        }
                        
                        $dropdownHtml .= '</select>';
                        $dropdownHtml .= '<svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">';
                        $dropdownHtml .= '<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />';
                        $dropdownHtml .= '</svg>';
                        $dropdownHtml .= '</span>';
                        return $dropdownHtml;
                    } else {
                        $pattern = count($gapOptions) ? ' pattern="'.htmlspecialchars(implode('|', array_column($gapOptions, 'option_text'))).'"' : '';
                        return '<input type="text" name="answers['.$question->question_id.']['.$gapId.']"'.$pattern.' class="inline-block align-baseline text-xs font-normal text-gray-700 placeholder-gray-400 italic bg-white/80 border border-gray-300 rounded-md hover:border-blue-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-300 transition-all duration-150 ease-in-out px-2 py-1 mx-1 w-20" required>';
                    }
                }, $question->question_text) !!}
              </p>
            @elseif ($question->question_type === 'multiple_choice')
              <div class="flex justify-center items-center mb-4">
                <p class="text-gray-100 font-medium text-lg">{{ $question->question_text }}</p>
              </div>
              @if($question->explanation)
                <div class="flex justify-center items-center mb-4">
                  <p class="text-gray-300 font-medium text-md">{{ $question->explanation }}</p>
                </div>
              @endif
              @php
                $options = json_decode($question->options, true);
              @endphp
              <div class="grid grid-cols-2 gap-4">
                <input type="radio" name="answers[{{ $question->question_id }}]" value="NotSelected" checked hidden>
                @foreach ($options as $option)
                  <div class="option">
                    <label class="block text-gray-300 cursor-pointer">
                      <input type="radio" name="answers[{{ $question->question_id }}]" value="{{ $option }}" class="hidden peer" required>
                      <div class="px-6 py-3 rounded-lg border border-gray-300 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500 transition-all text-center">
                        {{ $option }}
                      </div>
                    </label>
                  </div>
                @endforeach
              </div>
            @elseif ($question->question_type === 'short_answer')
              <div class="flex justify-center items-center mb-4">
                <p class="text-gray-100 font-medium text-lg">{{ $question->question_text }}</p>
              </div>
              @if($question->explanation)
                <div class="flex justify-center items-center mb-4">
                  <p class="text-gray-300 font-medium text-md">{{ $question->explanation }}</p>
                </div>
              @endif
              <textarea name="answers[{{ $question->question_id }}]" class="w-full border border-gray-300 p-3 rounded-md bg-white/80 text-gray-800 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-300 transition-all" placeholder="Your answer..." required></textarea>
            @elseif ($question->question_type === 'true_false')
              <div class="flex justify-center items-center mb-4">
                <p class="text-gray-100 font-medium text-lg">{{ $question->question_text }}</p>
              </div>
              @if($question->explanation)
                <div class="flex justify-center items-center mb-4">
                  <p class="text-gray-300 font-medium text-md">{{ $question->explanation }}</p>
                </div>
              @endif
              <div class="grid grid-cols-2 gap-4">
                <div class="option">
                  <label class="block text-gray-300 cursor-pointer">
                    <input type="radio" name="answers[{{ $question->question_id }}]" value="true" class="hidden peer" required>
                    <div class="px-6 py-3 rounded-lg border border-gray-300 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500 transition-all text-center">
                      True
                    </div>
                  </label>
                </div>
                <div class="option">
                  <label class="block text-gray-300 cursor-pointer">
                    <input type="radio" name="answers[{{ $question->question_id }}]" value="false" class="hidden peer" required>
                    <div class="px-6 py-3 rounded-lg border border-gray-300 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500 transition-all text-center">
                      False
                    </div>
                  </label>
                </div>
                <input type="radio" name="answers[{{ $question->question_id }}]" value="NotSelected" checked hidden>
              </div>
            @endif
          </div>
        @endforeach
  
        <div class="text-center mt-8">
          <button type="submit" id="submitBtn" class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-8 py-3 rounded-lg hover:from-indigo-600 hover:to-purple-600 shadow-lg text-lg font-medium transition-all duration-200">
            Submit Your Answers
          </button>
        </div>
      </form>
    </div>
  
    <!-- Full JavaScript for Timer and Form Logic -->
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const startTestButton = document.getElementById("start-test");
        const testForm = document.getElementById("test-form");
        const timerElement = document.getElementById("timer");
        const timerDisplay = document.getElementById("timer-display");
        const timerBar = document.getElementById("timer-bar");
        let timeTaken = 0;
        const testKey = "test_timer_{{ $test_attempt->test_attempt_token }}";
        const totalTime = {{ $test->time_limit_minutes * 60 }};
        let startTime = localStorage.getItem(testKey);
        let cheating = "";
  
        function getRemainingTime() {
          const elapsed = Math.floor((Date.now() - startTime) / 1000);
          return Math.max(totalTime - elapsed, 0);
        }
  
        function updateTimerDisplay(seconds) {
          const hours = Math.floor(seconds / 3600);
          const minutes = Math.floor((seconds % 3600) / 60);
          const secondsLeft = seconds % 60;
          const formattedTime = `${hours}:${minutes < 10 ? "0" : ""}${minutes}:${secondsLeft < 10 ? "0" : ""}${secondsLeft}`;
          timerElement.textContent = formattedTime;
          timerDisplay.textContent = `Time Left: ${formattedTime}`;
        }
  
        function autoSubmit() {
          localStorage.removeItem(testKey);
          testForm.submit();
        }
  
        function startTimer() {
          let remainingTime = getRemainingTime();
          updateTimerDisplay(remainingTime);
          const countdown = setInterval(function () {
            remainingTime--;
            timeTaken++;
            if (remainingTime <= 0) {
              clearInterval(countdown);
              document.getElementById("time_taken").value = timeTaken;
              autoSubmit();
            }
            updateTimerDisplay(remainingTime);
          }, 1000);
        }
  
        document.addEventListener("visibilitychange", function () {
          if (document.visibilityState === "hidden") {
            cheating = "You have switched tabs or minimized the window.";
            document.getElementById("cheating").value = cheating;
            document.getElementById("time_taken").value = timeTaken;
            testForm.submit();
          }
        });
  
        startTestButton.addEventListener("click", function () {
          // Reveal the questions form and hide the start section
          testForm.classList.remove("hidden");
          document.getElementById("start-section").classList.add("hidden");
          // Show the fixed timer bar
          timerBar.classList.remove("hidden");
          startTime = Date.now();
          localStorage.setItem(testKey, startTime);
  
          // Request fullscreen for added focus (if supported)
          if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
          } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
          } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen();
          } else if (document.documentElement.msRequestFullscreen) {
            document.documentElement.msRequestFullscreen();
          }
  
          startTimer();
        });
  
        document.addEventListener("fullscreenchange", function () {
          if (!document.fullscreenElement) {
            cheating = "You have exited fullscreen mode.";
            document.getElementById("cheating").value = cheating;
            setTimeout(function () {
              document.getElementById("time_taken").value = timeTaken;
              testForm.submit();
            }, 500);
          }
        });
  
        function validateForm() {
          let isValid = true;
          let errorContainer = document.getElementById("error-container");
          if (!errorContainer) {
            errorContainer = document.createElement("p");
            errorContainer.id = "error-container";
            errorContainer.classList.add("text-red-500", "font-medium", "mt-4", "text-center");
          }
          errorContainer.textContent = "Seems like you missed something. Please complete all questions.";
  
          document.querySelectorAll("input[name^='answers']").forEach((input) => {
            if (input.type === "radio") {
              if (input.checked && input.value !== "NotSelected") {
                // Valid radio selection
              }
            } else if ((input.type === "text" || input.tagName === "SELECT") && input.required && input.value.trim() === "") {
              isValid = false;
            }
          });
  
          document.querySelectorAll("textarea[name^='answers']").forEach((textarea) => {
            if (textarea.required && textarea.value.trim() === "") {
              isValid = false;
            }
          });
  
          if (!isValid) {
            testForm.appendChild(errorContainer);
          }
  
          return isValid;
        }
  
        document.getElementById("submitBtn").addEventListener("click", function (event) {
          if (validateForm()) {
            this.disabled = true;
            document.getElementById("time_taken").value = timeTaken;
            testForm.submit();
          } else {
            event.preventDefault();
            this.disabled = true;
            setTimeout(() => {
              this.disabled = false;
            }, 3000);
          }
        });
      });
    </script>
  </x-layout>