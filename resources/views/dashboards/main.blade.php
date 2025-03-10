<x-layout class="bg-[#080A25] min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section with Glassmorphism -->
        <div class="flex justify-between items-center mb-8 backdrop-blur-sm bg-gray-900/50 p-6 rounded-2xl border border-gray-700/30 z-10">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-400 to-purple-300 bg-clip-text text-transparent">
                @if(auth()->user()->permissions == "teacher")
                    Student Analytics Dashboard
                @else
                    My Learning Journey
                @endif
            </h1>
            <a href="{{ auth()->user()->permissions == 'teacher' ? route('TestDashboard') : url('/test') }}" 
               class="bg-gradient-to-r from-indigo-600 to-purple-500 hover:from-indigo-500 hover:to-purple-400 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg hover:shadow-indigo-500/30">
                {{ auth()->user()->permissions == 'teacher' ? 'Manage Tests' : '+ New Test' }}
            </a>
        </div>

        @if(auth()->user()->permissions == "teacher")
            <!-- Teacher View -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Chart Container -->
                <div class="backdrop-blur-lg bg-gray-900/50 p-8 rounded-2xl border border-gray-700/30 shadow-xl">
                    <h2 class="text-xl font-semibold text-indigo-200 mb-6">Class Performance Overview</h2>
                    <div class="h-80">
                        <canvas id="testScoreChart" class="rounded-lg"></canvas>
                    </div>
                </div>
                
                <!-- Top Students -->
                <div class="backdrop-blur-lg bg-gray-900/50 p-6 rounded-2xl border border-gray-700/30">
                    <h2 class="text-xl font-semibold text-indigo-200 mb-6">Top Performers</h2>
                    <div class="space-y-4">
                        @foreach($topStudents as $student)
                        <div class="group bg-gray-800/40 p-4 rounded-xl border border-gray-700/30 hover:bg-gray-700/30 transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-medium text-white">{{ $student->user->name }}</p>
                                    <p class="text-indigo-300 text-sm">Avg: {{ number_format($student->average_score, 1) }}%</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="bg-gradient-to-r from-emerald-600 to-teal-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        #{{ $loop->iteration }}
                                    </span>
                                    <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold">
                                        {{ $loop->iteration }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- All Attempts Table -->
            <div class="backdrop-blur-lg bg-gray-900/50 p-6 rounded-2xl border border-gray-700/30">
                <h2 class="text-xl font-semibold text-indigo-200 mb-6">All Attempt Records</h2>
                <div class="overflow-x-auto rounded-xl border border-gray-700/30">
                    <table class="w-full">
                        <thead class="bg-gray-800/60">
                            <tr>
                                <th class="px-6 py-4 text-left text-indigo-300 font-semibold">Student</th>
                                <th class="px-6 py-4 text-left text-indigo-300 font-semibold">Test</th>
                                <th class="px-6 py-4 text-left text-indigo-300 font-semibold">Score</th>
                                <th class="px-6 py-4 text-left text-indigo-300 font-semibold">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/30">
                            @foreach($attempts as $attempt)
                            <tr class="hover:bg-gray-800/30 transition-colors duration-200">
                                <td class="px-6 py-4 text-white font-medium">{{ $attempt->user->name }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $attempt->test->test_name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block py-1 px-3 rounded-full text-sm font-medium {{ $attempt->score >= 50 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                                        {{ $attempt->score }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400">{{ $attempt->attempt_date->format('M d, Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif(auth()->user()->permissions == "student")
            <!-- Student View -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Progress Chart -->
                <div class="backdrop-blur-lg bg-gray-900/50 p-8 rounded-2xl border border-gray-700/30">
                    <h2 class="text-xl font-semibold text-indigo-200 mb-6">Progress Timeline</h2>
                    <div class="h-80">
                        <canvas id="progressChart" class="rounded-lg"></canvas>
                    </div>
                </div>
                
                <!-- Recent Attempts -->
                <div class="backdrop-blur-lg bg-gray-900/50 p-6 rounded-2xl border border-gray-700/30">
                    <h2 class="text-xl font-semibold text-indigo-200 mb-6">Recent Attempts</h2>
                    <div class="overflow-y-auto h-[500px] custom-scrollbar">
                        @if($attempts->isNotEmpty())
                            <div class="space-y-4 pr-3">
                                @foreach($attempts as $attempt)
                                <div class="group bg-gray-800/40 p-5 rounded-xl border border-gray-700/30 hover:border-indigo-500/30 transition-all duration-300">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="text-lg font-medium text-white">{{ $attempt->test->test_name }}</p>
                                            <p class="text-indigo-300 text-sm">{{ $attempt->attempt_date->diffForHumans() }}</p>
                                        </div>
                                        <span class="text-lg font-bold {{ $attempt->score >= 50 ? 'text-emerald-400' : 'text-rose-400' }}">
                                            {{ $attempt->score }}%
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-400">{{ $attempt->time_spent }}</span>
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-20 bg-gray-700 rounded-full overflow-hidden">
                                                <div class="h-full {{ $attempt->score >= 50 ? 'bg-emerald-500' : 'bg-rose-500' }}" 
                                                     style="width: {{ $attempt->score }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-full flex items-center justify-center text-gray-400">
                                No attempts yet!
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(99,102,241,0.5);
            border-radius: 4px;
        }
    </style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(auth()->user()->permissions == "teacher")
            // Teacher's Test Averages Chart
            @isset($chartData)
new Chart(document.getElementById('testScoreChart'), {
    type: 'scatter',
    data: {
        datasets: [{
            label: 'Average Score (%)',
            data: @json($chartData['scores']).map((score, index) => ({
                x: index,  // Index-based positioning (hidden on axis)
                y: score
            })),
            backgroundColor: 'rgba(99, 102, 241, 0.8)',
            borderColor: 'rgba(99, 102, 241, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: { 
                    color: '#fff',
                    stepSize: 20
                }
            },
            x: {
                type: 'linear',
                display: true,  // Keep axis line but hide labels
                ticks: {
                    display: false  // Completely hide x-axis labels
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
                    boxWidth: 0
                }
            },
            tooltip: {
                displayColors: false,  // Hide color box in tooltip
                callbacks: {
                    title: (context) => {
                        // Show student name as title
                        const labels = @json($chartData['labels']);
                        return labels[context[0].parsed.x];
                    },
                    label: (context) => {
                        // Show only score in label
                        return `Average Score: ${context.parsed.y.toFixed(2)}%`;
                    }
                }
            }
        }
    }
});
@endisset

        @else
            // Student's Progress Chart
            @isset($progressData)
            new Chart(document.getElementById('progressChart'), {
                type: 'line',
                data: {
                    labels: @json($progressData['labels']),
                    datasets: [{
                        label: 'Your Scores',
                        data: @json($progressData['scores']),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: { color: '#fff' }
                        },
                        x: {
                            ticks: { color: '#fff' }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: { color: '#fff' }
                        }
                    }
                }
            });
            @endisset
        @endif
    });
</script>
</x-layout>