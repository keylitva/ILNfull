<x-layout class="bg-[#080A25] min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div
            class="flex justify-between items-center mb-8 backdrop-blur-sm bg-gray-900/50 p-6 rounded-2xl border border-gray-700/30">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-400 to-purple-300 bg-clip-text text-transparent">
                Test Results & Analytics – {{ $test->test_name ?? 'Test' }}
            </h1>
            <a href="{{ route('TestDashboard') }}"
                class="bg-gradient-to-r from-indigo-600 to-purple-500 hover:from-indigo-500 hover:to-purple-400 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg hover:shadow-indigo-500/30 hover:md:h-6/10">
                Back to Dashboard
            </a>
        </div>

        <!-- Graphs Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Question Performance Chart -->
            <div class="backdrop-blur-lg bg-gray-900/50 p-8 rounded-2xl border border-gray-700/30 shadow-xl">
                <h2 class="text-xl font-semibold text-indigo-200 mb-6">Question Performance</h2>
                <div class="h-80">
                    @if (isset($questionData) && count($questionData['labels']) > 0)
                        <canvas id="questionChart"></canvas>
                    @else
                        <div class="text-gray-400 text-center py-4">No question data available</div>
                    @endif
                </div>
            </div>

            <!-- Student Performance Chart -->
            <div class="backdrop-blur-lg bg-gray-900/50 p-8 rounded-2xl border border-gray-700/30 shadow-xl">
                <h2 class="text-xl font-semibold text-indigo-200 mb-6">Student Performance</h2>
                <div class="h-80">
                    @if (isset($studentData) && count($studentData['labels']) > 0)
                        <canvas id="studentChart"></canvas>
                    @else
                        <div class="text-gray-400 text-center py-4">No student data available</div>
                    @endif
                </div>
            </div>
        </div>

        
        <div class="mb-8 backdrop-blur-lg bg-gray-900/50 p-6 rounded-2xl border border-gray-700/30">
            <h2 class="text-xl font-semibold text-indigo-200 mb-4 flex items-center">
                <svg class="w-8 h-8 mr-3 fill-current text-indigo-200" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 258" width="256" height="258">
                    <defs>
                        <radialGradient cx="78.3021802%" cy="55.5203129%" fx="78.3021802%" fy="55.5203129%"
                            r="78.1147983%"
                            gradientTransform="translate(0.783022,0.555203),scale(0.999475,1.000000),rotate(78.858000),translate(-0.783022,-0.555203)"
                            id="radialGradient-1">
                            <stop stop-color="#1BA1E3" offset="0%"></stop>
                            <stop stop-color="#1BA1E3" offset="0.01%"></stop>
                            <stop stop-color="#5489D6" offset="30.0221%"></stop>
                            <stop stop-color="#9B72CB" offset="54.5524%"></stop>
                            <stop stop-color="#D96570" offset="82.5372%"></stop>
                            <stop stop-color="#F49C46" offset="100%"></stop>
                        </radialGradient>
                        <radialGradient cx="-3.4086494%" cy="-54.2187761%" fx="-3.4086494%" fy="-54.2187761%"
                            r="169.363094%"
                            gradientTransform="translate(-0.034086,-0.542188),scale(0.999462,1.000000),rotate(78.858000),translate(0.034086,0.542188)"
                            id="radialGradient-2">
                            <stop stop-color="#1BA1E3" offset="0%"></stop>
                            <stop stop-color="#1BA1E3" offset="0.01%"></stop>
                            <stop stop-color="#5489D6" offset="30.0221%"></stop>
                            <stop stop-color="#9B72CB" offset="54.5524%"></stop>
                            <stop stop-color="#D96570" offset="82.5372%"></stop>
                            <stop stop-color="#F49C46" offset="100%"></stop>
                        </radialGradient>
                    </defs>
                    <g>
                        <path
                            d="M122.062138,172.769563 L111.792605,196.29076 C107.844875,205.33202 95.3333209,205.33202 91.3858292,196.29076 L81.116058,172.769563 C71.9768317,151.837202 55.526844,135.175312 35.0075623,126.067072 L6.74034181,113.519526 C-2.24678895,109.530321 -2.24677226,96.4562364 6.74034181,92.4670316 L34.1246742,80.3113505 C55.1716865,70.9688024 71.9167648,53.6893195 80.8998648,32.0430683 L91.3026413,6.97665083 C95.162893,-2.32553558 108.015303,-2.32555703 111.875793,6.976627 L122.278569,32.0433067 C131.261669,53.6893195 148.006509,70.9688024 169.053521,80.3113505 L196.438068,92.4670316 C205.42522,96.4562364 205.42522,109.530321 196.438068,113.519526 L168.170633,126.067072 C147.65159,135.175312 131.201602,151.837202 122.062138,172.769563 Z"
                            fill="url(#radialGradient-1)"></path>
                        <path
                            d="M217.500574,246.936928 L214.612119,253.556205 C212.498336,258.402078 205.788482,258.402078 203.674699,253.556205 L200.786483,246.936928 C195.63813,235.134498 186.365183,225.737603 174.794201,220.596878 L165.895957,216.643427 C161.084646,214.505808 161.084646,207.51159 165.895957,205.373971 L174.296742,201.641481 C186.165198,196.368465 195.604282,186.623803 200.664204,174.420449 L203.630126,167.267483 C205.69719,162.282168 212.589628,162.282168 214.656692,167.267483 L217.622615,174.420449 C222.682536,186.623803 232.121858,196.368465 243.990792,201.641481 L252.390623,205.373971 C257.203126,207.51159 257.203126,214.505808 252.390623,216.643427 L243.492618,220.596878 C231.921874,225.737603 222.648689,235.134498 217.500574,246.936928 Z"
                            fill="url(#radialGradient-2)"></path>
                    </g>
                </svg>
                AI Feedback & Recommendations
            </h2>
            <div id="aiFeedbackContainer">
                <div class="flex items-center justify-center py-8" id="aiLoading">
                    <svg class="animate-spin h-8 w-8 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-3 text-indigo-200">Analyzing results with AI...</span>
                </div>
                <div id="aiContent" class="hidden"></div>
            </div>
        </div>

        <!-- Test Attempts Table -->
        <div class="backdrop-blur-lg bg-gray-900/50 p-6 rounded-2xl border border-gray-700/30">
            <h2 class="text-xl font-semibold text-indigo-200 mb-6">All Test Attempts</h2>
            <div class="overflow-x-auto rounded-xl border border-gray-700/30 custom-scrollbar">
                <table class="w-full">
                    <thead class="bg-gray-800/60">
                        <tr>
                            <th class="px-6 py-4 text-left text-indigo-300 font-semibold">Student</th>
                            <th class="px-6 py-4 text-left text-indigo-300 font-semibold">Score</th>
                            <th class="px-6 py-4 text-left text-indigo-300 font-semibold">Time Taken</th>
                            <th class="px-6 py-4 text-left text-indigo-300 font-semibold">Date</th>
                            <th class="px-6 py-4 text-left text-indigo-300 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/30">
                        @forelse($attempts as $attempt)
                            <tr class="hover:bg-gray-800/30 transition-colors duration-200">
                                <td class="px-6 py-4 text-white font-medium">{{ $attempt->user->name }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-block py-1 px-3 rounded-full text-sm font-medium {{ $attempt->score >= 50 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                                        {{ $attempt->score }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-300">{{ $attempt->time_taken }} mins</td>
                                <td class="px-6 py-4 text-gray-400">
                                    {{ \Carbon\Carbon::parse($attempt->attempt_date)->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('test.results', $attempt->test_attempt_token) }}"
                                        class="bg-gradient-to-r from-indigo-600 to-purple-500 hover:from-indigo-500 hover:to-purple-400 text-white px-4 py-2 rounded-md font-semibold transition-all duration-300 hover:shadow-lg hover:shadow-indigo-500/30">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-gray-400 text-center">No attempts recorded yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Question Performance Chart
            @if (isset($questionData) && count($questionData['labels']) > 0)
                const questionCtx = document.getElementById('questionChart').getContext('2d');
                new Chart(questionCtx, {
                    type: 'line',
                    data: {
                        labels: @json($questionData['labels']),
                        datasets: [{
                            label: '% Correct',
                            data: @json($questionData['percentages']),
                            backgroundColor: 'rgba(99, 102, 241, 0.8)',
                            borderColor: 'rgba(99, 102, 241, 1)',
                            borderWidth: 1,
                            borderRadius: 8,
                            barPercentage: 0.7,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 110,
                                ticks: {
                                    color: '#fff',
                                    stepSize: 20,
                                    callback: function(value) {
                                        return value <= 100 ? value + '%' : ''; // Hide ticks above 100%
                                    }
                                },
                                grid: {
                                    color: function(context) {
                                        return context.tick.value > 100 ? 'transparent' :
                                            'rgba(255, 255, 255, 0.1)'; // Hide grid line above 100%
                                    },
                                    borderDash: [5],
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#fff',
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#fff',
                                    font: {
                                        weight: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                padding: 12
                            }
                        }
                    }
                });
            @endif

            // Student Performance Chart
            @if (isset($studentData) && count($studentData['labels']) > 0)
                const studentCtx = document.getElementById('studentChart').getContext('2d');
                new Chart(studentCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($studentData['labels']),
                        datasets: [{
                            label: 'Score (%)',
                            data: @json($studentData['scores']),
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1,
                            borderRadius: 8,
                            barPercentage: 0.7,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 110,
                                ticks: {
                                    color: '#fff',
                                    stepSize: 20,
                                    callback: function(value) {
                                        return value <= 100 ? value + '%' : ''; // Hide ticks above 100%
                                    }
                                },
                                grid: {
                                    color: function(context) {
                                        return context.tick.value > 100 ? 'transparent' :
                                            'rgba(255, 255, 255, 0.1)'; // Hide grid line above 100%
                                    },
                                    borderDash: [5],
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#fff',
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#fff',
                                    font: {
                                        weight: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                padding: 12
                            }
                        }
                    }
                });
            @endif
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.5);
            border-radius: 4px;
            transition: background 0.3s;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.8);
        }
    </style>
</x-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const aiContainer = document.getElementById('aiFeedbackContainer');
        const loadingEl = document.getElementById('aiLoading');
        const contentEl = document.getElementById('aiContent');

        // Only fetch if there are attempts
        @if(!$attempts->isEmpty())
            fetch("{{ route('test.ai-feedback', $test->test_id) }}")
                .then(response => response.json())
                .then(data => {
                    loadingEl.remove();
                    contentEl.innerHTML = data.feedback;
                    contentEl.classList.remove('hidden');
                })
                .catch(error => {
                    loadingEl.innerHTML = '<p class="text-rose-400">Failed to load AI feedback. Please try refreshing the page.</p>';
                });
        @else
            aiContainer.remove();
        @endif
    });
</script>