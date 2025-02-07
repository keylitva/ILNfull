<x-layout class="h-full bg-[#080A25]">
    <div class="h-full bg-[#080A25] flex flex-col items-center py-10 px-4">
        <!-- Test Title -->
        <div class="text-center mb-6">
            <h1 class="text-4xl font-bold text-white tracking-wide shadow-lg bg-[#080A25] px-6 py-4 rounded-md">
                {{ $test->test_name }}
            </h1>
        </div>

        <!-- Test Attempt Details -->
        <div class="bg-white p-8 rounded-md shadow-lg w-full max-w-3xl mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Test Attempt Information</h2>
            <p class="text-gray-700 mb-2">
                <strong>Attempt Date:</strong> {{ \Carbon\Carbon::parse($test_attempt->attempt_date)->format('F d, Y H:i') }}
            </p>
            <p class="text-gray-700 mb-2">
                <strong>Total Questions:</strong> {{ $questions->count() }}
            </p>
            <p class="text-gray-700 mb-2">
                <strong>Time Limit: </strong> {{ $test->time_limit_minutes }} minutes
            </p>
            <p class="text-gray-700">
                <strong>Important:</strong> Answer all questions and click submit when you're finished.
            </p>
        </div>

        <!-- Instructions -->
        <div class="bg-gray-200 p-8 rounded-md shadow-lg w-full max-w-3xl mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Test Instructions</h2>
            <ul class="list-disc list-inside text-gray-700">
                <li>Each question type may require different input methods such as text, dropdowns, or checkboxes.</li>
                <li>Some questions allow multiple answers, others only one.</li>
                <li>You can navigate between questions freely and submit when ready.</li>
            </ul>
        </div>

        <!-- Start Test Button -->
        <div class="bg-white p-8 rounded-md shadow-lg w-full max-w-3xl mb-8 text-center">
            <p id="timer" class="text-xl text-gray-800 font-semibold mb-4">Time Left: {{ \Carbon\Carbon::parse($test->time_limit_minutes * 60)->format('H:i:s') }}</p>
            <button id="start-test" class="bg-[#080A25] text-white px-8 py-3 rounded-lg hover:bg-gray-700 shadow-md text-lg font-medium">
                Start Test
            </button>
        </div>

        <!-- Questions (Initially hidden) -->
        <form action="/submit_test" method="POST" class="bg-white p-8 rounded-md shadow-lg w-full max-w-3xl hidden" id="test-form">
            @csrf
            <input type="hidden" name="test_attempt_token" value="{{ $test_attempt->test_attempt_token }}">
            <input type="hidden" name="cheating" id="cheating" value="">
            <input type="hidden" name="time_taken" id="time_taken" value="">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Test Questions</h2>

            @foreach ($questions as $index => $question)
    <div class="mb-6 border-b pb-4">
        <div class="flex justify-center items-center p-4">
            <p class="text-gray-800 font-medium text-lg">{{ $index + 1 }}</p>
        </div>

        @if ($question->question_type === 'fill_in_the_gaps' || $question->question_type === 'gap_fill_with_choices')
        
        <div class="flex justify-center items-center p-4">
            <p class="text-gray-600 font-medium text-md">{{ $question->explanation }}</p>
        </div>
            <p class="text-gray-800 font-medium text-lg">
                {!! preg_replace_callback('/\[(.*?)\]/', function ($matches) use ($question) {
                    // Extract the gap type and gap ID from the placeholder
                    $gap = $matches[1]; // e.g., "dropdown_1" or "input_2"
                    $gapId = (int) filter_var($gap, FILTER_SANITIZE_NUMBER_INT);

                    // Parse the options JSON from the question
                    $options = json_decode($question->options, true);

                    // Find options that belong to this gap ID
                    $gapOptions = array_filter($options, function ($option) use ($gapId) {
                        return $option['gap_id'] === $gapId;
                    });

                    if (str_contains($gap, 'dropdown')) {
                        // Render inline dropdown with arrow
                        $dropdownHtml = '<span class="inline-block relative align-middle mx-1">';
                        $dropdownHtml .= '<select name="answers[' . $question->question_id . '][' . $gapId . ']" class="text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg shadow-sm hover:border-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-300 focus:ring-opacity-50 transition-all duration-200 ease-in-out pl-2 pr-6 py-1 w-auto appearance-none" style="min-width: 120px;" required>';
                        $dropdownHtml .= '<option value="" class="text-gray-400 italic">Choose</option>';
                        foreach ($gapOptions as $option) {
                            $dropdownHtml .= '<option value="' . $option['option_id'] . '" class="text-gray-700 hover:bg-blue-50">' . htmlspecialchars($option['option_text']) . '</option>';
                        }
                        $dropdownHtml .= '</select>';
                        $dropdownHtml .= '<svg class="absolute right-1 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">';
                        $dropdownHtml .= '<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />';
                        $dropdownHtml .= '</svg>';
                        $dropdownHtml .= '</span>';
                        return $dropdownHtml;
                    } elseif (str_contains($gap, 'input')) {
                        return '<input type="text" name="answers[' . $question->question_id . '][' . $gapId . ']" class="inline-block align-baseline text-xs font-normal text-gray-700 placeholder-gray-400 italic bg-white border border-gray-300 rounded-md hover:border-blue-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-300 transition-all duration-150 ease-in-out px-1.5 py-0.5 mx-0.5 w-[6ch] max-w-[8ch]" style="letter-spacing: 0.5px" required>';
                    }

                    return ''; // Fallback for unsupported gap types
                }, $question->question_text) !!}
            </p>
        @elseif ($question->question_type === 'multiple_choice')
            <div class="flex justify-center items-center p-4">
                <p class="text-gray-800 font-medium text-lg">{{ $question->question_text }}</p>
            </div>
            <div class="flex justify-center items-center p-4">
                <p class="text-gray-600 font-medium text-md">{{ $question->explanation }}</p>
            </div>
            @php
                $options = json_decode($question->options, true);
            @endphp
            <div class="grid grid-cols-2 gap-4 p-4">
                <input type="radio" name="answers[{{ $question->question_id }}]" value="NotSelected" checked hidden>
                @foreach ($options as $key => $option)
                    <div class="option w-full">
                        <label class="block w-full text-gray-600 cursor-pointer">
                            <input type="radio" name="answers[{{ $question->question_id }}]" value="{{$option}}" class="hidden peer" required>
                            <div class="w-full px-6 py-3 rounded-lg border border-gray-300 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500 cursor-pointer transition-all text-center whitespace-normal"> 
                                {{ $option }}
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        @elseif ($question->question_type === 'short_answer')
            <div class="flex justify-center items-center p-4">
                <p class="text-gray-800 font-medium text-lg">{{ $question->question_text }}</p>
            </div>
            <div class="flex justify-center items-center p-4">
                <p class="text-gray-600 font-medium text-md">{{ $question->explanation }}</p>
            </div>
            <textarea name="answers[{{ $question->question_id }}]" class="border p-2 rounded-md w-full" placeholder="Your answer..." required></textarea>
        @elseif ($question->question_type === 'true_false')
            <div class="flex justify-center items-center p-4">
                <p class="text-gray-800 font-medium text-lg">{{ $question->question_text }}</p>
            </div>
            <div class="flex justify-center items-center p-4">
                <p class="text-gray-600 font-medium text-md">{{ $question->explanation }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4 p-4">
                <div class="option w-full">
                    <label class="block w-full text-gray-600 cursor-pointer">
                        <input type="radio" name="answers[{{ $question->question_id }}]" value="true" class="hidden peer" required>
                        <div class="w-full px-6 py-3 rounded-lg border border-gray-300 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500 cursor-pointer transition-all text-center whitespace-normal"> 
                            True
                        </div>
                    </label>
                </div>
                <div class="option w-full">
                    <label class="block w-full text-gray-600 cursor-pointer">
                        <input type="radio" name="answers[{{ $question->question_id }}]" value="false" class="hidden peer" required>
                        <div class="w-full px-6 py-3 rounded-lg border border-gray-300 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500 cursor-pointer transition-all text-center whitespace-normal"> 
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
                <button type="submit" id="submitBtn" class="bg-[#080A25] text-white px-8 py-3 rounded-lg hover:bg-gray-700 shadow-md text-lg font-medium">
                    Submit Your Answers
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const startTestButton = document.getElementById("start-test");
            const testForm = document.getElementById("test-form");
            const timerElement = document.getElementById("timer");
            const errorContainer = document.createElement("p");
    
            let timeTaken = 0;
            let testKey = "test_timer_{{ $test_attempt->test_attempt_token }}";
            let totalTime = {{ $test->time_limit_minutes * 60 }};
            let startTime = localStorage.getItem(testKey);
            let cheating = "";
    
            function getRemainingTime() {
                let elapsed = Math.floor((Date.now() - startTime) / 1000);
                return Math.max(totalTime - elapsed, 0);
            }
    
            function updateTimerDisplay(seconds) {
                let hours = Math.floor(seconds / 3600); // Calculate hours
                let minutes = Math.floor((seconds % 3600) / 60); // Calculate minutes
                let secondsLeft = seconds % 60; // Remaining seconds
    
                // Format the time display to include hours, minutes, and seconds
                timerElement.textContent = `Time Left: ${hours}:${minutes < 10 ? "0" : ""}${minutes}:${secondsLeft < 10 ? "0" : ""}${secondsLeft}`;
            }
    
            function autoSubmit() {
                localStorage.removeItem(testKey);
                testForm.submit();
            }
    
            function startTimer() {
                let remainingTime = getRemainingTime();
                updateTimerDisplay(remainingTime);
                let countdown = setInterval(function () {
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
                testForm.classList.remove("hidden");
                startTestButton.classList.add("hidden");
                startTime = Date.now();
                localStorage.setItem(testKey, startTime);
    
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
                errorContainer.textContent = "Seems like you missed something. Please complete all questions.";
                errorContainer.classList.add("text-red-500", "font-medium", "mt-4");
                errorContainer.style.display = "flex";
                errorContainer.style.justifyContent = "center";
                errorContainer.style.alignItems = "center";
                errorContainer.style.textAlign = "center";
    
                let errorMessage = ""; // To store the error message dynamically.
    
                // Validate Multiple Choice & True/False (inputs with name="answers[]")
                let radioChecked = false;
                document.querySelectorAll("input[name^='answers']").forEach((input) => {
                    // Check for radio buttons
                    if (input.type === "radio") {
                        if (input.checked && input.value !== "NotSelected") {
                            radioChecked = true;
                        }
                    }
                    // Check for text inputs or select elements
                    else if ((input.type === "text" || input.tagName === "SELECT") && input.required && input.value.trim() === "") {
                        isValid = false;
                        errorMessage = "Please fill in all blanks and dropdowns.";
                    }
                });
    
                if (!radioChecked) {
                    isValid = false;
                    errorMessage = "Please answer all multiple-choice and true/false questions.";
                }
    
                // Validate Short Answer & Essay (inputs with name="answers[]")
                document.querySelectorAll("textarea[name^='answers']").forEach((textarea) => {
                    if (textarea.required && textarea.value.trim() === "") {
                        isValid = false;
                        errorMessage = "Please complete all short-answer and essay questions.";
                    }
                });
    
                if (!isValid) {
                    testForm.appendChild(errorContainer);
                }
    
                return isValid;
            }
    
            document.getElementById("submitBtn").addEventListener("click", function (event) {
                if (validateForm()) {
                    this.disabled = true; // Disable the button to prevent multiple submissions
                    document.getElementById("time_taken").value = timeTaken;
                    testForm.submit();
                } else {
                    event.preventDefault();
                    this.disabled = true; // Disable the button to prevent multiple submissions
                    setTimeout(() => {
                        this.disabled = false; // Re-enable the button after a few seconds
                    }, 3000); // 3 seconds delay
                }
            });

            
        });
    </script>
</x-layout>