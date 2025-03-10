<x-layout class="h-full bg-[#080A25]">
    <div id="loading-screen" class="fixed inset-0 flex flex-col items-center justify-center bg-black/60 z-50">
        <div class="spinner mb-4"></div>
        <div class="text-white text-lg font-semibold">Loading...</div>
    </div>

    <style>
        /* Loading Screen Styles */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            /* semi-transparent background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 1;
            transition: opacity 0.5s ease, visibility 0s 0.5s;
            /* smooth fade-out */
        }

        #loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .spinner {
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-top: 8px solid #6366F1;
            /* Indigo-500 */
            border-radius: 50%;
            width: 64px;
            height: 64px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="min-h-screen bg-[#080A25] flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">
        <form
            action="{{ request()->is('tests/edit/*') ? url('tests/store/' . request()->segment(3)) : url('tests/store') }}"
            method="POST"
            class="w-full max-w-4xl bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-8 sm:p-10 transition-all duration-300 hover:shadow-3xl">
            @csrf
            <!-- Form Header -->
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h1
                        class="text-4xl font-bold bg-gradient-to-r from-indigo-400 to-purple-300 bg-clip-text text-transparent">
                        {{ request()->is('tests/edit/*') ? 'Update Test' : 'Create New Test' }}
                    </h1>
                    <p class="text-slate-300 mt-3 text-lg">Craft your perfect assessment with dynamic questions</p>
                </div>
                <a href="{{ route('TestDashboard') }}"
                    class="bg-gradient-to-r from-indigo-600 to-purple-500 hover:from-indigo-500 hover:to-purple-400 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg hover:shadow-indigo-500/30 flex items-center transform hover:scale-105">
                    Back to Dashboard
                </a>
            </div>

            <!-- Test Details -->
            <div class="space-y-8 mb-14">
                <div>
                    <label class="block text-sm font-semibold text-slate-200 mb-3">Test Name</label>
                    <input type="text" name="test_name" id="test_name" required
                        class="w-full px-5 py-3.5 rounded-xl border border-white/20 bg-white/5 text-slate-200 placeholder-slate-400 
                     focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all
                     @error('test_name') border-red-400 @enderror"
                        value="{{ old('test_name') }}">
                    @error('test_name')
                        <p class="mt-2 text-sm text-red-400 flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-200 mb-3">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-5 py-3.5 rounded-xl border border-white/20 bg-white/5 text-slate-200 placeholder-slate-400 
                     focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all
                     @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-400 flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-wrap gap-6">
                    <div class="w-full sm:w-72">
                        <label class="block text-sm font-semibold text-slate-200 mb-3">Time Limit (Minutes)</label>
                        <input type="number" name="time_limit_minutes" id="time_limit_minutes" required
                            class="w-full px-5 py-3.5 rounded-xl border border-white/20 bg-white/5 text-slate-200 placeholder-slate-400 
                                   focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all
                                   @error('time_limit_minutes') border-red-400 @enderror"
                            value="{{ old('time_limit_minutes', 45) }}">
                        @error('time_limit_minutes')
                            <p class="mt-2 text-sm text-red-400 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="relative w-full sm:w-72">
                        <label class="block text-sm font-semibold text-slate-200 mb-3">Is Active</label>
                        <select name="is_active" id="is_active" required
                            class="w-full px-5 py-3 pr-12 rounded-xl bg-white/5 border border-white/20 text-slate-200 
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none transition-all">
                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>True</option>
                            <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>False</option>
                        </select>
                        <svg class="absolute right-4 top-1/2 -translate-y-[-20%] w-5 h-5 text-slate-400 pointer-events-none"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        @error('is_active')
                            <p class="mt-2 text-sm text-red-400 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="border-t border-white/20 pt-10">
                <h2 class="text-2xl font-semibold text-slate-200 mb-8">Assessment Questions</h2>
                <div id="questions-container" class="space-y-10"></div>

                <!-- Add Question Button -->
                <button type="button" onclick="addQuestion()"
                    class="mt-10 w-full border-2 border-dashed border-indigo-500/30 hover:border-indigo-500/50 rounded-2xl p-7 transition-all duration-300 group">
                    <div class="flex items-center justify-center gap-3 text-indigo-400 group-hover:text-indigo-300">
                        <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="font-medium text-lg">Add Question</span>
                    </div>
                </button>
            </div>

            <!-- Submit Button -->
            <div class="mt-14">
                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl active:scale-[0.98]">
                    {{ request()->is('tests/edit/*') ? 'Update Assessment' : 'Create Assessment' }}
                </button>
            </div>
        </form>
    </div>
</x-layout>

<style>
    .gap-pill {
        display: inline-flex;
        align-items: center;
        background: rgba(99, 102, 241, 0.2);
        border: 1px solid rgba(99, 102, 241, 0.5);
        color: #818cf8;
        border-radius: 9999px;
        padding: 0.25rem 0.75rem;
        margin: 0 0.25rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        position: relative;
        cursor: default;
        user-select: none;
    }

    .gap-pill::after {}

    .remove-gap {
        margin-left: 0.5rem;
        cursor: pointer;
        color: #6366f1;
        font-weight: bold;
        padding: 0 0.25rem;
    }

    .remove-gap:hover {
        color: #818cf8;
    }

    .question-editor {
        min-height: 120px;
        padding: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        overflow-wrap: break-word;
    }

    .question-editor:focus {
        outline: none;
        border-color: rgba(99, 102, 241, 0.8);
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.3);
    }

    .draggable-gap {
        cursor: move;
    }

    .gateway-section {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 1.5rem;
    }

    .gateway-header {
        padding: 1rem 1.5rem;
        background: rgba(99, 102, 241, 0.15);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .gateway-title {
        color: #818cf8;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .config-section {
        padding: 1.5rem;
    }

    .insert-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .dropdown-options {
        margin-top: 1rem;
        padding-left: 1rem;
        border-left: 2px solid rgba(99, 102, 241, 0.3);
    }

    .option-item {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.875rem;
        padding: 0.25rem 0;
    }
</style>

<script>
    function showLoadingScreen() {
        document.getElementById('loading-screen').style.display = 'flex';
    }

    function hideLoadingScreen() {
        const loader = document.getElementById('loading-screen');
        loader.classList.add('hidden');

        // You can optionally remove it from the DOM completely after the animation is done
        setTimeout(() => {
            loader.style.display = 'none'; // Ensure it's hidden after the fade-out
        }, 500); // The timeout should match the duration of the fade-out (500ms in this case)
    }

    showLoadingScreen();

    let questionIndex = 0;
    // For each question, map gapId to an object { type, correctAnswer, gapNumber }
    const gapConfigs = new Map();
    // Keep a counter for gap numbering per question
    const gapCounter = {};

    function addQuestion() {
        const container = document.getElementById('questions-container');
        const questionDiv = document.createElement('div');
        questionDiv.classList.add(
            'question', 'bg-white/5', 'rounded-2xl', 'p-7', 'border',
            'border-white/20', 'shadow-sm', 'hover:border-white/30', 'transition-colors'
        );

        questionDiv.innerHTML = `
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
              <h3 class="text-xl font-semibold text-slate-200 mb-3 sm:mb-0">
                Question ${questionIndex + 1}
              </h3>
              <div class="relative w-full sm:w-72">
                <select name="questions[${questionIndex}][type]" onchange="handleQuestionTypeChange(${questionIndex}, this.value)"
                  class="w-full px-5 py-3 pr-12 rounded-xl bg-white/5 border border-white/20 text-slate-200 
                         focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none transition-all">
                  <option value="fill_in_the_gaps">Fill in Gaps</option>
                  <option value="multiple_choice">Multiple Choice</option>
                  <option value="short_answer">Short Answer</option>
                  <option value="true_false">True/False</option>
                </select>
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" 
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            
            <div class="question-editor-wrapper" data-question-index="${questionIndex}">
              <div class="question-editor text-white" 
                   contenteditable="true"
                   placeholder="Enter question text..."
                   oninput="updateHiddenQuestionText(${questionIndex})"></div>
              <input type="hidden" name="questions[${questionIndex}][text]" id="hidden-question-${questionIndex}">
            </div>
            
            <div id="question-extra-${questionIndex}" class="mt-6"></div>
          `;

        container.appendChild(questionDiv);
        // Initialize gap configuration storage for this question
        gapConfigs.set(questionIndex, new Map());
        // Initialize gap counter for sequential numbering for this question
        gapCounter[questionIndex] = 1;

        initializeEditor(questionIndex);
        handleQuestionTypeChange(questionIndex, 'fill_in_the_gaps');
        questionIndex++;
    }

    function initializeEditor(qIndex) {
        const editor = document.querySelector(`[data-question-index="${qIndex}"] .question-editor`);

        editor.addEventListener('paste', e => {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text/plain');
            document.execCommand('insertText', false, text);
        });

        editor.addEventListener('keydown', e => {
            if (e.key === 'Backspace' || e.key === 'Delete') {
                const selection = window.getSelection();
                const node = selection.anchorNode.parentNode;
                if (node.classList && node.classList.contains('gap-pill')) {
                    e.preventDefault();
                    removeGapPill(qIndex, node.dataset.gapId);
                }
            }
        });
    }

    function handleQuestionTypeChange(qIndex, type) {
        const extraDiv = document.getElementById(`question-extra-${qIndex}`);

        switch (type) {
            case 'fill_in_the_gaps':
                extraDiv.innerHTML = `
                <div class="gap-configuration">
                  <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-slate-300">Gap Configuration</span>
                    <button type="button" onclick="addGapConfiguration(${qIndex})"
                      class="text-sm bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-400 hover:text-indigo-300 font-medium flex items-center gap-2 px-4 py-2 rounded-xl transition-all">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                      </svg>
                      Add Gap
                    </button>
                  </div>
                  <div id="gap-config-${qIndex}" class="space-y-4"></div>
                </div>
              `;
                break;

            case 'multiple_choice':
                const optionsContainer = document.getElementById(
                    `question-extra-${qIndex}`); // Dynamically selecting the container based on the question index

                // Clear any existing content in the options container
                optionsContainer.innerHTML = ''; // Optional: Clear previous options before appending new ones

                // Create options dynamically
                [1, 2, 3, 4].forEach(option => {
                    const optionDiv = document.createElement('div');
                    optionDiv.classList.add(
                        'flex', 'items-center', 'gap-3', 'py-2', 'relative', 'group',
                        'rounded-lg', 'border', 'border-white/20', 'bg-white/5', 'px-4',
                        'hover:bg-white/10', 'transition-all', 'cursor-pointer',
                        'mb-4' // Added margin bottom to create a gap between each option
                    );

                    optionDiv.innerHTML = `
          <input type="radio" name="questions[${qIndex}][correct_option]" value="${option}" 
                 class="sr-only peer">
          <div class="w-4 h-4 flex-shrink-0 rounded-full border border-white/30 bg-white/10 transition-all 
                      peer-checked:bg-indigo-500 peer-checked:border-indigo-500"></div>
          <input type="text" name="questions[${qIndex}][options][]" placeholder="Option ${option}" 
                 class="w-full px-3 py-2 text-sm rounded-md bg-transparent text-slate-200 placeholder-slate-400 
                        border border-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all 
                        peer-checked:text-indigo-300 peer-checked:border-indigo-500" required>
        `;

                    // Attach click event listener to select the radio button when optionDiv is clicked
                    optionDiv.addEventListener('click', () => {
                        const radio = optionDiv.querySelector('input[type="radio"]');
                        radio.checked = true;
                    });

                    // Append optionDiv to the options container (question-extra-${qIndex})
                    optionsContainer.appendChild(optionDiv);
                });
                break;
            case 'short_answer':
                extraDiv.innerHTML = `
        <div class="mt-6">
          <label class="block text-sm font-semibold text-slate-300 mb-3">Correct Answer</label>
          <input type="text" name="questions[${qIndex}][correct_answer]" 
            class="w-full px-4 py-3 rounded-xl border border-white/20 bg-white/5 text-slate-200 placeholder-slate-400 
                   focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
            placeholder="Enter correct answer" required>
        </div>
      `;
                break;
            case 'true_false':
                extraDiv.innerHTML = `
        <div class="mt-6">
          <label class="block text-sm font-semibold text-slate-300 mb-4">Correct Answer</label>
          <div class="flex items-center gap-8">
            <div class="flex items-center gap-3 mb-4 rounded-lg border border-white/20 bg-white/5 px-4 py-2 transition-all cursor-pointer hover:bg-white/10" id="true-option-${qIndex}">
              <input type="radio" name="questions[${qIndex}][correct_answer]" value="true" class="sr-only peer">
              <div class="w-4 h-4 flex-shrink-0 rounded-full border border-white/30 bg-white/10 transition-all peer-checked:bg-indigo-500 peer-checked:border-indigo-500"></div>
              <span class="text-slate-200">True</span>
            </div>
            <div class="flex items-center gap-3 mb-4 rounded-lg border border-white/20 bg-white/5 px-4 py-2 transition-all cursor-pointer hover:bg-white/10" id="false-option-${qIndex}">
              <input type="radio" name="questions[${qIndex}][correct_answer]" value="false" class="sr-only peer">
              <div class="w-4 h-4 flex-shrink-0 rounded-full border border-white/30 bg-white/10 transition-all peer-checked:bg-indigo-500 peer-checked:border-indigo-500"></div>
              <span class="text-slate-200">False</span>
            </div>
          </div>
        </div>
    `;

                // Get the True and False option divs
                const trueOption = document.getElementById(`true-option-${qIndex}`);
                const falseOption = document.getElementById(`false-option-${qIndex}`);

                // Add click event listeners to select the radio button when an option is clicked
                trueOption.addEventListener('click', () => {
                    const radio = trueOption.querySelector('input[type="radio"]');
                    radio.checked = true;
                });

                falseOption.addEventListener('click', () => {
                    const radio = falseOption.querySelector('input[type="radio"]');
                    radio.checked = true;
                });
                break;
        }
    }

    function addGapConfiguration(qIndex) {
        const configContainer = document.getElementById(`gap-config-${qIndex}`);
        const gapId = Date.now().toString(); // Ensure unique gap ID
        const gapNumber = gapCounter[qIndex]++; // Increment counter for each gap
        const gapConfigsForQuestion = gapConfigs.get(qIndex);
        gapConfigsForQuestion.set(gapId, {
            type: 'input',
            correctAnswer: '',
            gapNumber: gapNumber
        });

        const gapConfig = document.createElement('div');
        gapConfig.className = 'gap-config-item bg-white/5 rounded-xl p-4 border border-white/20';
        gapConfig.dataset.gapId = gapId;
        gapConfig.id = `gap-config-item-${qIndex}-${gapId}`; // Unique ID for targeting

        gapConfig.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-slate-300">Gap ${gapNumber}</span>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="insertGapPill(${qIndex}, '${gapId}')"
                    class="text-sm bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-400 hover:text-indigo-300 px-3 py-1.5 rounded-lg transition-all">
                    Insert
                </button>
                <div class="relative">
                    <select onchange="updateGapType(${qIndex}, '${gapId}', this.value)"
                        class="text-sm rounded-xl border-2 border-gray-800 bg-transparent text-slate-200 placeholder-slate-400 
                               px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none pr-10">
                        <option value="input">Input</option>
                        <option value="dropdown">Dropdown</option>
                    </select>
                    <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-white/70 pointer-events-none" 
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <button type="button" onclick="deleteFullGap(${qIndex}, '${gapId}')"
                    class="text-red-400 hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="mt-3">
            <input type="text" id="correct-answer-${qIndex}-${gapId}"
                name="questions[${qIndex}][gaps][${gapId}][correct_answer]"
                class="w-full px-3 py-2 text-sm rounded-xl border border-white/20 bg-white/5 text-slate-200"
                placeholder="Correct answer" required>
        </div>
        <div id="gap-options-${qIndex}-${gapId}" class="mt-3"></div>
    `;

        configContainer.appendChild(gapConfig);
    }

    function addDropdownOption(qIndex, gapId) {
        const optionsContainer = document.getElementById(`dropdown-options-${qIndex}-${gapId}`);
        const optionCount = optionsContainer.children.length + 1;
        const optionId = `dropdown-option-${qIndex}-${gapId}-${Date.now()}`; // Generate a unique ID

        const optionDiv = document.createElement('div');
        optionDiv.id = optionId; // Assign unique ID for direct targeting
        optionDiv.classList.add(
            'flex', 'items-center', 'gap-3', 'py-2', 'mb-2', 'relative', 'group',
            'rounded-lg', 'border', 'border-white/20', 'bg-white/5', 'px-4',
            'hover:bg-white/10', 'transition-all', 'cursor-pointer'
        );

        optionDiv.innerHTML = `
        <input type="radio" name="questions[${qIndex}][gaps][${gapId}][correct_option]" value="${optionCount}" 
            class="sr-only peer">
        <div class="w-4 h-4 flex-shrink-0 rounded-full border border-white/30 bg-white/10 transition-all 
            peer-checked:bg-indigo-500 peer-checked:border-indigo-500"></div>
        <input type="text" 
            name="questions[${qIndex}][gaps][${gapId}][options][]" 
            class="w-full px-3 py-2 text-sm rounded-md bg-transparent text-slate-200 placeholder-slate-400 
                   border border-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all 
                   peer-checked:text-indigo-300 peer-checked:border-indigo-500"
            placeholder="Option ${optionCount}">
        <button type="button" onclick="deleteDropdownOption('${optionId}')" class="text-red-400 hover:text-red-300 ml-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;

        // Ensure clicking anywhere in the option div selects the radio button
        optionDiv.addEventListener('click', (event) => {
            if (!event.target.closest('button')) {
                const radio = optionDiv.querySelector('input[type="radio"]');
                radio.checked = true;
            }
        });

        optionsContainer.appendChild(optionDiv);
    }

    function deleteDropdownOption(optionId) {
        const optionDiv = document.getElementById(optionId);
        if (optionDiv) {
            optionDiv.remove();
        }
    }

    function insertGapPill(qIndex, gapId) {
        const editor = document.querySelector(`[data-question-index="${qIndex}"] .question-editor`);
        const selection = window.getSelection();

        if (!selection.rangeCount) return;

        const range = selection.getRangeAt(0);
        // Ensure we have an element to call .closest() on:
        let commonAncestor = range.commonAncestorContainer;
        if (commonAncestor.nodeType !== Node.ELEMENT_NODE) {
            commonAncestor = commonAncestor.parentElement;
        }

        // Ensure the selection is inside the correct .question-editor and not outside
        if (!commonAncestor.closest('.question-editor-wrapper[data-question-index="' + qIndex + '"]')) {
            alert('Gap can only be inserted inside the question text box!');
            return;
        }

        // Prevent insertion inside another gap pill
        const parentGap = commonAncestor.closest('.gap-pill');
        if (parentGap) {
            alert('Cannot insert gap inside another gap!');
            return;
        }

        // Check if the gap pill already exists for this gap ID in the editor
        const existingPill = editor.querySelector(`.gap-pill[data-gap-id="${gapId}"]`);
        if (existingPill) {
            alert('This gap is already inserted!');
            return;
        }

        // Retrieve the sequential gap number from the configuration
        const gapNumber = gapConfigs.get(qIndex).get(gapId).gapNumber;
        const gapPill = createGapPill(qIndex, gapId, gapNumber);

        // Insert the gap pill at the selection point
        range.insertNode(gapPill);
        range.setStartAfter(gapPill);
        range.collapse(true);
        selection.removeAllRanges();
        selection.addRange(range);

        updateHiddenQuestionText(qIndex);
    }

    function createGapPill(qIndex, gapId, gapNumber) {
        const pill = document.createElement('span');
        pill.className = 'gap-pill';
        pill.dataset.gapId = gapId;
        pill.dataset.gapNumber = gapNumber;
        pill.contentEditable = false;

        // Create the remove button ("×") and place it BEFORE the gap text.
        const removeBtn = document.createElement('span');
        removeBtn.className = 'remove-gap';
        removeBtn.innerHTML = '×';
        removeBtn.style.marginLeft = '0rem';
        removeBtn.style.marginRight = '0.5rem';
        removeBtn.onclick = () => removeGapPill(qIndex, gapId);

        pill.appendChild(removeBtn);
        // Append the gap text (only showing "gap {number}")
        pill.appendChild(document.createTextNode(`Gap ${gapNumber}`));

        return pill;
    }

    function removeGapPill(qIndex, gapId) {
        removeGapPillFromEditor(gapId);
        updateHiddenQuestionText(qIndex);
    }

    function removeGapPillFromEditor(gapId) {
        const pills = document.querySelectorAll(`.gap-pill[data-gap-id="${gapId}"]`);
        pills.forEach(pill => pill.remove());
        updateAllHiddenInputs();
    }

    function deleteFullGap(qIndex, gapId) {
        // Remove the gap configuration element from the config UI
        const configItem = document.querySelector(`#gap-config-${qIndex} [data-gap-id="${gapId}"]`);
        if (configItem) configItem.remove();

        // Remove the gap from the configuration
        const gapConfigsForQuestion = gapConfigs.get(qIndex);
        gapConfigsForQuestion.delete(gapId);

        // Remove any inserted gap pills from the editor
        removeGapPillFromEditor(gapId);
    }

    function updateGapType(qIndex, gapId, type) {
        const gapConfig = document.getElementById(`gap-config-item-${qIndex}-${gapId}`);
        const correctAnswerInput = document.getElementById(`correct-answer-${qIndex}-${gapId}`);
        const optionsDiv = document.getElementById(`gap-options-${qIndex}-${gapId}`);

        if (type === 'dropdown') {
            // Hide the input field
            correctAnswerInput.style.display = 'none';
            correctAnswerInput.disabled = true;
            correctAnswerInput.removeAttribute('required');

            // Insert dropdown options
            optionsDiv.innerHTML = `
      <div class="mt-3">
        <button type="button" onclick="addDropdownOption(${qIndex}, '${gapId}')"
                class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-3 py-1.5 rounded-lg text-sm transition-all">
          Add Option
        </button>
        <div id="dropdown-options-${qIndex}-${gapId}" class="mt-3 space-y-3"></div>
      </div>
    `;
        } else {
            // Show the input field again
            correctAnswerInput.style.display = 'inline-block';
            correctAnswerInput.disabled = false;
            correctAnswerInput.setAttribute('required', 'required');

            // Clear the options
            optionsDiv.innerHTML = '';
        }

        // Update gapConfig object (optional for further updates if required)
        const gapConfigsForQuestion = gapConfigs.get(qIndex);
        const gapConfigData = gapConfigsForQuestion.get(gapId);
        gapConfigData.type = type; // Update the gap type
    }

    function updateHiddenQuestionText(qIndex) {
        const editor = document.querySelector(`[data-question-index="${qIndex}"] .question-editor`);
        const hiddenInput = document.getElementById(`hidden-question-${qIndex}`);
        const text = Array.from(editor.childNodes).map(node => {
            if (node.nodeType === Node.TEXT_NODE) return node.textContent;
            if (node.classList && node.classList.contains('gap-pill')) return `[gap_${node.dataset.gapId}]`;
            return '';
        }).join('');

        hiddenInput.value = text.trim();
    }

    function updateAllHiddenInputs() {
        document.querySelectorAll('.question-editor-wrapper').forEach(wrapper => {
            const qIndex = wrapper.dataset.questionIndex;
            updateHiddenQuestionText(qIndex);
        });
    }

    // Helper: returns a promise that resolves when the element appears in the DOM.
    function waitForElement(selector, interval = 50, timeout = 5000, retries = 5) {
        return new Promise((resolve, reject) => {
            const startTime = Date.now();
            let attempts = 0;

            const timer = setInterval(() => {
                const el = document.querySelector(selector);
                if (el) {
                    clearInterval(timer);
                    resolve(el);
                } else if (Date.now() - startTime > timeout || attempts >= retries) {
                    clearInterval(timer);
                    reject(`Timeout waiting for element: ${selector}`);
                }
                attempts++;
            }, interval);
        });
    }

    function autoPopulateTestIfEdit() {
        // Only run if test data is provided from the backend
        @if (isset($test) && $test->questions)
            //console.log('Test data exists. Starting population...');

            // Populate test details
            document.getElementById('test_name').value = @json($test->test_name);
            document.getElementById('description').value = @json($test->description);
            document.getElementById('time_limit_minutes').value = @json($test->time_limit_minutes);
            document.getElementById('is_active').value = @json($test->is_active);
            var questions = @json($test->questions);

            let totalQuestions = @json(count($test->questions));
            let loadedQuestions = 0;
            const markQuestionAsLoaded = () => {
                loadedQuestions++;
                //console.log(`Loaded ${loadedQuestions} out of ${totalQuestions} questions.`);
                if (loadedQuestions === totalQuestions) {
                    hideLoadingScreen();
                }
            };

            // Loop through each test question provided by the backend
            questions.forEach((question, index) => {
                //console.log(`Processing question ${index + 1}...`);
                // Create the question UI – this also increments questionIndex and initializes gapConfigs & gapCounter for the question
                addQuestion();

                let qIndex = questionIndex - 1;

                //console.log(`Creating hidden input for question ID for question ${qIndex}...`);
                // Insert a hidden field for the question id if it exists
                let questionContainer = document.querySelector(`[data-question-index="${qIndex}"]`);
                if (questionContainer) {
                    let idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = `questions[${qIndex}][id]`;
                    idInput.value = question.question_id;
                    questionContainer.appendChild(idInput);
                }

                let questionData = {
                    text: question.question_text,
                    type: question.question_type,
                    options: JSON.parse(question.options),
                    correct_answer: JSON.parse(question.correct_answer)
                };

                //console.log(`Question data for question ${qIndex}:`, questionData);

                // Set the question type selector and trigger its change handler (to load extra UI)
                let typeSelect = document.querySelector(`[name="questions[${qIndex}][type]"]`);
                if (typeSelect) {
                    typeSelect.value = questionData.type;
                    handleQuestionTypeChange(qIndex, questionData.type);
                }

                // Use a timeout to ensure that the DOM updates (editor & extra UI) are complete
                setTimeout(() => {
                    if (questionData.type === 'fill_in_the_gaps') {
                        // console.log(`Processing "fill_in_the_gaps" question ${qIndex}...`);
                        // Get the saved question text containing gap markers (which look like [gap_...])
                        let questionText = questionData.text;
                        // Reset the gap counter for this question (it was also set in addQuestion(), but we need to reset it for prefill)
                        gapCounter[qIndex] = 1;
                        // Find all gap markers in the text
                        let gapMarkers = questionText.match(/\[gap_[^\]]+\]/g) || [];

                        //  console.log(`Found ${gapMarkers.length} gap markers in question ${qIndex}.`);

                        // For each gap marker (using its order/index)
                        gapMarkers.forEach((marker, gapIndex) => {
                            // Create a new gap configuration.
                            addGapConfiguration(qIndex);

                            // Get the gapConfig Map for this question and retrieve the last-added gap ID.
                            const gapConfigsForQuestion = gapConfigs.get(qIndex);
                            const gapIds = Array.from(gapConfigsForQuestion.keys());
                            const newGapId = gapIds[gapIds.length - 1];

                            // Prepare default prefill values
                            let prefillCorrectAnswer = '';
                            let prefillType = 'input';
                            let prefillOptions = [];

                            //console.log(`Prefilling gap ${newGapId} for question ${qIndex}...`);

                            // If there is saved data for this gap (by order), use it:
                            if (questionData.correct_answer && questionData.correct_answer
                                .length > gapIndex) {
                                // prefillCorrectAnswer may be an answer string (or null)
                                prefillCorrectAnswer = questionData.correct_answer[gapIndex]
                                    .answer || '';
                                // For matching options, we use the DB’s stored gap id for this gap
                                const origGapId = questionData.correct_answer[gapIndex].gap_id;
                                if (questionData.options && questionData.options.length) {
                                    // Filter the options that were saved for this gap using the original DB gap id
                                    prefillOptions = questionData.options.filter(opt => opt
                                        .gap_id == origGapId);
                                    // If any option exists and its input_type is dropdown, then override the gap type
                                    if (prefillOptions.length > 0 && prefillOptions[0]
                                        .input_type === 'dropdown') {
                                        prefillType = 'dropdown';
                                    }
                                }
                            }

                            // Update the gap configuration UI with the prefill data
                            let gapConfigElem = document.getElementById(
                                `gap-config-item-${qIndex}-${newGapId}`);
                            if (gapConfigElem) {
                                // Update the gap type select in this config item
                                let typeSelectElem = gapConfigElem.querySelector('select');
                                if (typeSelectElem) {
                                    typeSelectElem.value = prefillType;
                                    updateGapType(qIndex, newGapId, prefillType);
                                }
                                // If the gap is an input type, auto-fill the correct answer
                                let correctAnswerInput = document.getElementById(
                                    `correct-answer-${qIndex}-${newGapId}`);
                                if (correctAnswerInput && prefillType === 'input') {
                                    correctAnswerInput.value = prefillCorrectAnswer;
                                }
                                // If the gap is dropdown, pre-populate the options
                                if (prefillType === 'dropdown' && prefillOptions.length > 0) {
                                    let optionsDiv = document.getElementById(
                                        `gap-options-${qIndex}-${newGapId}`);
                                    if (optionsDiv) {
                                        // Render the dropdown UI (button and container)
                                        optionsDiv.innerHTML = `
                                    <div class="mt-3">
                                      <button type="button" onclick="addDropdownOption(${qIndex}, '${newGapId}')"
                                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-3 py-1.5 rounded-lg text-sm transition-all">
                                        Add Option
                                      </button>
                                      <div id="dropdown-options-${qIndex}-${newGapId}" class="mt-3 space-y-3"></div>
                                    </div>
                                  `;
                                        let dropdownOptionsContainer = document.getElementById(
                                            `dropdown-options-${qIndex}-${newGapId}`);
                                        // For each saved option for this gap, create an option element.
                                        prefillOptions.forEach((option, idx) => {
                                            let optionDiv = document.createElement(
                                                'div');
                                            // Create a unique option id (we use Date.now() plus index to try to keep it unique)
                                            let optionId =
                                                `dropdown-option-${qIndex}-${newGapId}-${Date.now()}-${idx}`;
                                            optionDiv.id = optionId;
                                            optionDiv.classList.add(
                                                'flex', 'items-center', 'gap-3',
                                                'py-2',
                                                'relative', 'group',
                                                'rounded-lg', 'border',
                                                'border-white/20',
                                                'bg-white/5', 'px-4',
                                                'hover:bg-white/10',
                                                'transition-all',
                                                'cursor-pointer'
                                            );
                                            // If this option’s text equals the saved correct answer, mark it checked.
                                            let checkedAttribute = (option
                                                    .option_text ===
                                                    prefillCorrectAnswer) ? 'checked' :
                                                '';
                                            optionDiv.innerHTML = `
                                            <input type="radio" name="questions[${qIndex}][gaps][${newGapId}][correct_option]" value="${option.option_id}" 
                                                class="sr-only peer" ${checkedAttribute}>
                                            <div class="w-4 h-4 flex-shrink-0 rounded-full border border-white/30 bg-white/10 transition-all 
                                                peer-checked:bg-indigo-500 peer-checked:border-indigo-500"></div>
                                            <input type="text" 
                                                name="questions[${qIndex}][gaps][${newGapId}][options][]" 
                                                class="w-full px-3 py-2 text-sm rounded-md bg-transparent text-slate-200 placeholder-slate-400 
                                                border border-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all 
                                                peer-checked:text-indigo-300 peer-checked:border-indigo-500"
                                                value="${option.option_text}">
                                            <button type="button" onclick="deleteDropdownOption('${optionId}')" class="text-red-400 hover:text-red-300 ml-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                            `;
                                            // When the optionDiv (outside of the button) is clicked, select the corresponding radio button.
                                            optionDiv.addEventListener('click', (
                                                event) => {
                                                if (!event.target.closest(
                                                        'button')) {
                                                    let radio = optionDiv
                                                        .querySelector(
                                                            'input[type="radio"]'
                                                        );
                                                    if (radio) radio.checked =
                                                        true;
                                                }
                                            });
                                            dropdownOptionsContainer.appendChild(
                                                optionDiv);
                                        });
                                    }
                                }
                            }

                            // Replace the gap marker in the question text with a gap pill element.
                            let gapPillElem = createGapPill(qIndex, newGapId, gapCounter[
                                qIndex] - 1);
                            questionText = questionText.replace(marker, gapPillElem.outerHTML);
                        });
                        // Update the question editor’s content with the modified text (now containing gap pills)
                        let editor = document.querySelector(
                            `[data-question-index="${qIndex}"] .question-editor`);
                        if (editor) {
                            editor.innerHTML = questionText;
                        }
                        updateHiddenQuestionText(qIndex);

                    } else if (questionData.type === 'multiple_choice') {
                        //console.log(`Processing "multiple_choice" question ${qIndex}...`);
                        let editor = document.querySelector(
                            `[data-question-index="${qIndex}"] .question-editor`);
                        // Set the question text
                        editor.innerHTML = questionData.text;

                        // Populate the options for the multiple-choice question
                        let optionsInputs = document.querySelectorAll(
                            `[name="questions[${qIndex}][options][]"]`);
                        questionData.options.forEach((optionText, idx) => {
                            if (optionsInputs[idx]) {
                                optionsInputs[idx].value =
                                    optionText; // Fill in the options values

                                // Now check if the option matches the correct answer
                                if (questionData.correct_answer.includes(optionText)) {
                                    // Find the corresponding radio button and set it as checked
                                    let correctRadioButton = document.querySelector(
                                        `[name="questions[${qIndex}][correct_option]"][value="${idx + 1}"]`
                                    );
                                    if (correctRadioButton) {
                                        correctRadioButton.checked = true;
                                    }
                                }
                            }
                        });
                        updateHiddenQuestionText(qIndex);
                    } else if (questionData.type === 'short_answer') {
                        //console.log(`Processing "short_answer" question ${qIndex}...`);
                        let editor = document.querySelector(
                            `[data-question-index="${qIndex}"] .question-editor`);
                        editor.innerHTML = questionData.text;
                        // Set the short answer correct value
                        let shortAnswerInput = document.querySelector(
                            `[name="questions[${qIndex}][correct_answer]"]`);
                        if (shortAnswerInput) {
                            shortAnswerInput.value = questionData.correct_answer;
                        }
                        updateHiddenQuestionText(qIndex);
                    } else if (questionData.type === 'true_false') {
                        //console.log(`Processing "true_false" question ${qIndex}...`);
                        let editor = document.querySelector(
                            `[data-question-index="${qIndex}"] .question-editor`);

                        editor.innerHTML = questionData.text;
                        // Mark the true/false radio button that matches the saved answer
                        let tfRadio = document.querySelector(
                            `[name="questions[${qIndex}][correct_answer]"][value="${questionData.correct_answer}"]`
                        );
                        if (tfRadio) {
                            tfRadio.checked = true;
                        }
                        updateHiddenQuestionText(qIndex);
                    }

                    // Mark the question as loaded
                    markQuestionAsLoaded();
                }, 1000); // Add a delay to ensure that the DOM is ready for manipulation

            });
        @else
            //console.log('No test data available to populate.');
        @endif
    }
    // Then modify the DOMContentLoaded listener like this:
    document.addEventListener('DOMContentLoaded', () => {
        autoPopulateTestIfEdit(); // This handles both new and edit cases
        @if (!isset($test))
            {
                addQuestion(); // Only add initial question for new tests
                window.addEventListener('load', () => {
                    hideLoadingScreen();
                });
            }
        @endif
    });
</script>
