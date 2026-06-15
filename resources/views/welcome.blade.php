<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enterprise System & AI Analytics MVP</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #030712;
            color: #f3f4f6;
        }
        .glass {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glow {
            box-shadow: 0 0 40px rgba(99, 102, 241, 0.15);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #030712;
        }
        ::-webkit-scrollbar-thumb {
            background: #1f2937;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #374151;
        }
    </style>
</head>
<body class="h-full antialiased overflow-hidden relative">

    <!-- background glow nodes -->
    <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] rounded-full bg-indigo-500/10 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[500px] h-[500px] rounded-full bg-purple-500/10 blur-[120px] pointer-events-none"></div>

    <div class="h-full flex flex-col relative z-10">
        <!-- TOP HEADER -->
        <header class="glass border-b border-gray-800/80 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-600/20 text-indigo-400 rounded-lg border border-indigo-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-semibold tracking-tight text-white">Enterprise Order & Service System</h1>
                    <p class="text-xs text-indigo-400 font-medium">AI-Driven Analytics MVP</p>
                </div>
            </div>

            <!-- User Status / Logout -->
            <div id="user-header-section" class="hidden flex items-center gap-4">
                <div class="text-right">
                    <p id="header-user-name" class="text-sm font-medium text-white"></p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-900/50 text-indigo-300 border border-indigo-500/30" id="header-user-role">
                        admin
                    </span>
                </div>
                <button onclick="logout()" class="px-3 py-1.5 bg-gray-800 hover:bg-red-900/30 hover:text-red-400 rounded-lg text-sm transition font-medium border border-gray-700">
                    Đăng xuất
                </button>
            </div>
        </header>

        <!-- MAIN WINDOW -->
        <div class="flex-1 flex overflow-hidden">
            
            <!-- LOGIN VIEW (Shown by default) -->
            <div id="login-view" class="flex-1 flex items-center justify-center p-6 overflow-y-auto">
                <div class="w-full max-w-md glass glow rounded-2xl p-8 border border-gray-800">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-white tracking-tight">Recruiter Sandbox Login</h2>
                        <p class="text-gray-400 text-sm mt-1">Sử dụng tài khoản Demo được cấp sẵn dưới đây</p>
                    </div>

                    <!-- Alert message container -->
                    <div id="login-alert" class="hidden mb-4 p-3 bg-red-900/30 border border-red-500/30 text-red-200 text-sm rounded-lg"></div>

                    <form onsubmit="handleLogin(event)" class="space-y-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                            <input type="email" id="login-email" required value="admin@example.com" class="w-full px-4 py-3 bg-gray-900/60 border border-gray-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white transition placeholder-gray-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Password</label>
                            <input type="password" id="login-password" required value="password" class="w-full px-4 py-3 bg-gray-900/60 border border-gray-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white transition placeholder-gray-600">
                        </div>
                        
                        <button type="submit" id="btn-login-submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl text-white font-medium transition shadow-lg shadow-indigo-600/25 flex justify-center items-center gap-2">
                            <span>Đăng nhập Sandbox</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>

                    <div class="mt-6 pt-6 border-t border-gray-800/80 text-center">
                        <p class="text-xs text-gray-500">
                            Hệ thống đã tự động chạy Database Seed để cấu hình sẵn dữ liệu cho buổi demo ứng tuyển.
                        </p>
                    </div>
                </div>
            </div>

            <!-- DASHBOARD VIEW (Hidden by default) -->
            <div id="dashboard-view" class="hidden flex-1 flex overflow-hidden">
                <!-- Sidebar Menu Tabs -->
                <aside class="w-64 glass border-r border-gray-800/80 flex flex-col justify-between p-4">
                    <nav class="space-y-1.5">
                        <button onclick="switchTab('analytics')" id="tab-btn-analytics" class="w-full flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-xl text-sm font-medium transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <span>Dashboard Báo cáo</span>
                        </button>
                        <button onclick="switchTab('orders')" id="tab-btn-orders" class="w-full flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800/50 hover:text-white rounded-xl text-sm font-medium transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span>Đơn hàng & Dịch vụ</span>
                        </button>
                        <button onclick="switchTab('ai-chat')" id="tab-btn-ai-chat" class="w-full flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800/50 hover:text-white rounded-xl text-sm font-medium transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            <span>Trợ lý Phân tích AI</span>
                        </button>
                    </nav>

                    <div class="p-3 bg-gray-900/50 rounded-xl border border-gray-800">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Môi trường</h4>
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-xs text-gray-300 font-medium">PostgreSQL Live</span>
                        </div>
                    </div>
                </aside>

                <!-- TAB CONTENT CONTAINER -->
                <main class="flex-1 overflow-y-auto p-8">
                    
                    <!-- TAB 1: ANALYTICS DASHBOARD -->
                    <div id="tab-analytics" class="space-y-8">
                        <div>
                            <h3 class="text-2xl font-bold text-white">Dashboard Báo cáo Phân tích</h3>
                            <p class="text-sm text-gray-400">Dữ liệu phân tích doanh thu được truy vấn theo thời gian thực</p>
                        </div>

                        <!-- Mini metrics cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="glass p-6 rounded-2xl">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Doanh thu Tích lũy (USD)</span>
                                <p id="metric-revenue" class="text-3xl font-bold text-white mt-2">$0.00</p>
                            </div>
                            <div class="glass p-6 rounded-2xl">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tổng Đơn hàng Thực tế</span>
                                <p id="metric-orders" class="text-3xl font-bold text-indigo-400 mt-2">0</p>
                            </div>
                            <div class="glass p-6 rounded-2xl">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tổng số Dịch vụ mẫu</span>
                                <p class="text-3xl font-bold text-purple-400 mt-2">3</p>
                            </div>
                        </div>

                        <!-- Data Blocks -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Sales by month -->
                            <div class="glass p-6 rounded-2xl space-y-4">
                                <h4 class="text-sm font-semibold text-white uppercase tracking-wider">Doanh thu theo Tháng</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-800 text-gray-400">
                                                <th class="pb-3 font-semibold">Tháng</th>
                                                <th class="pb-3 font-semibold text-right">Tổng Đơn</th>
                                                <th class="pb-3 font-semibold text-right">Doanh thu</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-monthly-sales" class="divide-y divide-gray-800/40">
                                            <tr>
                                                <td colspan="3" class="py-4 text-center text-gray-500">Đang tải dữ liệu...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Distribution by Service Type -->
                            <div class="glass p-6 rounded-2xl space-y-4">
                                <h4 class="text-sm font-semibold text-white uppercase tracking-wider">Phân bổ theo Loại Dịch vụ</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-800 text-gray-400">
                                                <th class="pb-3 font-semibold">Loại</th>
                                                <th class="pb-3 font-semibold text-right">Tổng Đơn</th>
                                                <th class="pb-3 font-semibold text-right">Doanh thu</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-service-dist" class="divide-y divide-gray-800/40">
                                            <tr>
                                                <td colspan="3" class="py-4 text-center text-gray-500">Đang tải dữ liệu...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: ORDERS & SERVICES -->
                    <div id="tab-orders" class="hidden space-y-8">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-white">Quản lý Đơn hàng & Dịch vụ</h3>
                                <p class="text-sm text-gray-400">Xem danh sách các đơn hàng hiện có hoặc khởi tạo đơn mới dưới dạng transaction</p>
                            </div>
                            <button onclick="openCreateOrderModal()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span>Tạo đơn hàng mới</span>
                            </button>
                        </div>

                        <!-- Orders List Table -->
                        <div class="glass rounded-2xl overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center bg-gray-900/30">
                                <h4 class="text-sm font-semibold text-white uppercase tracking-wider">Danh sách Đơn hàng</h4>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-800 text-gray-400 bg-gray-950/20">
                                            <th class="px-6 py-4.5 font-semibold">Mã Đơn hàng</th>
                                            <th class="px-6 py-4.5 font-semibold">Khách hàng</th>
                                            <th class="px-6 py-4.5 font-semibold">Trạng thái</th>
                                            <th class="px-6 py-4.5 font-semibold text-right">Tổng tiền</th>
                                            <th class="px-6 py-4.5 font-semibold">Hạn xử lý (SLA)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-orders-list" class="divide-y divide-gray-800/40">
                                        <tr>
                                            <td colspan="5" class="py-6 text-center text-gray-500">Đang tải danh sách đơn hàng...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: AI ANALYTICS CHAT -->
                    <div id="tab-ai-chat" class="hidden space-y-8 h-full flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-white">AI Analytics Copilot</h3>
                            <p class="text-sm text-gray-400">Trợ lý phân tích kinh doanh. Dựa trên luồng ngữ cảnh RAG (Retrieval-Augmented Generation) từ database.</p>
                        </div>

                        <!-- Suggestion Prompts -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button onclick="usePresetPrompt('Analyze last month\'s low-performing services')" class="text-left glass p-4 rounded-xl hover:border-indigo-500/50 hover:bg-indigo-950/10 transition group">
                                <p class="text-xs font-semibold text-indigo-400 group-hover:text-indigo-300">Gợi ý phân tích #1</p>
                                <p class="text-sm text-gray-300 mt-1">"Analyze last month's low-performing services"</p>
                            </button>
                            <button onclick="usePresetPrompt('Provide recommendation playbook for recovering delayed orders')" class="text-left glass p-4 rounded-xl hover:border-indigo-500/50 hover:bg-indigo-950/10 transition group">
                                <p class="text-xs font-semibold text-indigo-400 group-hover:text-indigo-300">Gợi ý phân tích #2</p>
                                <p class="text-sm text-gray-300 mt-1">"Provide recommendation playbook for recovering delayed orders"</p>
                            </button>
                        </div>

                        <!-- AI Response Box -->
                        <div class="flex-1 glass rounded-2xl p-6 min-h-[300px] overflow-y-auto flex flex-col justify-between">
                            <div id="ai-response-container" class="space-y-4">
                                <div class="p-4 bg-indigo-950/20 border border-indigo-500/20 rounded-xl text-sm text-indigo-200">
                                    Chào bạn, tôi là AI Analytics Copilot của bạn. Hãy gửi câu hỏi phân tích dữ liệu hoạt động hoặc chọn các mẫu câu hỏi gợi ý ở trên để tôi phân tích dữ liệu trong PostgreSQL.
                                </div>
                            </div>
                            
                            <div id="ai-loading" class="hidden flex items-center justify-center gap-3 p-4">
                                <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm text-gray-400 font-medium">Đang truy vấn PostgreSQL, tạo Embeddings & hỏi LLM...</span>
                            </div>
                        </div>

                        <!-- Chat Input Box -->
                        <form onsubmit="handleAIQuery(event)" class="flex gap-4">
                            <input type="text" id="ai-query-input" placeholder="Ví dụ: Analyze last month's low-performing services..." required class="flex-1 px-4 py-3 bg-gray-900/60 border border-gray-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white transition placeholder-gray-600 text-sm">
                            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition flex items-center gap-2">
                                <span>Gửi AI</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </form>
                    </div>

                </main>
            </div>
        </div>
    </div>

    <!-- MODAL CREATE ORDER -->
    <div id="modal-order" class="hidden fixed inset-0 z-50 flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm">
        <div class="w-full max-w-lg glass glow rounded-2xl p-6 border border-gray-800">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-white">Tạo đơn hàng mới (Transaction)</h3>
                <button onclick="closeCreateOrderModal()" class="text-gray-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form onsubmit="submitNewOrder(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Chọn Khách hàng</label>
                    <select id="order-customer" required class="w-full px-4 py-2.5 bg-gray-900/80 border border-gray-800 rounded-xl text-white focus:outline-none text-sm">
                        <option value="1">Acme Corporation (CUS-ACME)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Chọn Dịch vụ đăng ký</label>
                    <div class="space-y-3 bg-gray-950/40 p-4 rounded-xl border border-gray-800/60 text-sm">
                        <!-- SVC-CRM-OPS -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="svc-check-1" value="1" class="w-4 h-4 rounded border-gray-800 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="font-medium text-white">CRM Operations Support</p>
                                    <p class="text-xs text-gray-500">$1,200 | SLA: 48h</p>
                                </div>
                            </div>
                            <input type="number" id="svc-qty-1" value="1" min="1" class="w-16 px-2 py-1 bg-gray-900 border border-gray-800 rounded text-center text-white text-xs">
                        </div>
                        
                        <!-- SVC-DATA-DASH -->
                        <div class="flex items-center justify-between border-t border-gray-800/60 pt-3">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="svc-check-2" value="2" class="w-4 h-4 rounded border-gray-800 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="font-medium text-white">Data Analytics Dashboard</p>
                                    <p class="text-xs text-gray-500">$3,500 | SLA: 96h</p>
                                </div>
                            </div>
                            <input type="number" id="svc-qty-2" value="1" min="1" class="w-16 px-2 py-1 bg-gray-900 border border-gray-800 rounded text-center text-white text-xs">
                        </div>

                        <!-- SVC-AI-INSIGHT -->
                        <div class="flex items-center justify-between border-t border-gray-800/60 pt-3">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="svc-check-3" value="3" class="w-4 h-4 rounded border-gray-800 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="font-medium text-white">AI Business Insight Agent</p>
                                    <p class="text-xs text-gray-500">$2,200 | SLA: 72h</p>
                                </div>
                            </div>
                            <input type="number" id="svc-qty-3" value="1" min="1" class="w-16 px-2 py-1 bg-gray-900 border border-gray-800 rounded text-center text-white text-xs">
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="closeCreateOrderModal()" class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-xl text-sm font-medium transition">
                        Huỷ bỏ
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl text-sm transition">
                        Khởi tạo (Commit)
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS APP LOGIC -->
    <script>
        let token = localStorage.getItem('token') || null;
        let activeTab = 'analytics';

        // Check if already logged in on boot
        window.addEventListener('DOMContentLoaded', () => {
            if (token) {
                showDashboard();
            }
        });

        // ------------------ AUTHENTICATION ------------------
        async function handleLogin(e) {
            e.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            const alertBox = document.getElementById('login-alert');
            const submitBtn = document.getElementById('btn-login-submit');

            alertBox.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.innerText = 'Đang đăng nhập...';

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Email hoặc mật khẩu không chính xác.');
                }

                // Save to storage
                token = data.access_token;
                localStorage.setItem('token', token);
                localStorage.setItem('user', JSON.stringify(data.user));

                showDashboard();
            } catch (err) {
                alertBox.innerText = err.message;
                alertBox.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Đăng nhập Sandbox';
            }
        }

        function logout() {
            token = null;
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            
            document.getElementById('dashboard-view').classList.add('hidden');
            document.getElementById('user-header-section').classList.add('hidden');
            document.getElementById('login-view').classList.remove('hidden');
        }

        // ------------------ SWITCH VIEW & TABS ------------------
        function showDashboard() {
            const user = JSON.parse(localStorage.getItem('user'));
            
            document.getElementById('login-view').classList.add('hidden');
            document.getElementById('dashboard-view').classList.remove('hidden');
            document.getElementById('user-header-section').classList.remove('hidden');
            
            document.getElementById('header-user-name').innerText = user.name;
            document.getElementById('header-user-role').innerText = user.roles[0]?.name || 'User';

            // Refresh analytical data
            refreshData();
        }

        function switchTab(tab) {
            activeTab = tab;
            
            // Hide all tabs
            document.getElementById('tab-analytics').classList.add('hidden');
            document.getElementById('tab-orders').classList.add('hidden');
            document.getElementById('tab-ai-chat').classList.add('hidden');
            
            // Un-highlight sidebar btns
            document.getElementById('tab-btn-analytics').className = 'w-full flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800/50 hover:text-white rounded-xl text-sm font-medium transition';
            document.getElementById('tab-btn-orders').className = 'w-full flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800/50 hover:text-white rounded-xl text-sm font-medium transition';
            document.getElementById('tab-btn-ai-chat').className = 'w-full flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800/50 hover:text-white rounded-xl text-sm font-medium transition';

            // Show current active tab & btn
            if (tab === 'analytics') {
                document.getElementById('tab-analytics').classList.remove('hidden');
                document.getElementById('tab-btn-analytics').className = 'w-full flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-xl text-sm font-medium transition';
                fetchAnalytics();
            } else if (tab === 'orders') {
                document.getElementById('tab-orders').classList.remove('hidden');
                document.getElementById('tab-btn-orders').className = 'w-full flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-xl text-sm font-medium transition';
                fetchOrders();
            } else if (tab === 'ai-chat') {
                document.getElementById('tab-ai-chat').classList.remove('hidden');
                document.getElementById('tab-btn-ai-chat').className = 'w-full flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-xl text-sm font-medium transition';
            }
        }

        // ------------------ CALL API DATA ------------------
        async function refreshData() {
            await fetchAnalytics();
        }

        async function fetchAnalytics() {
            try {
                const response = await fetch('/api/admin/analytics/dashboard', {
                    headers: { 
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}` 
                    }
                });
                
                if (response.status === 401 || response.status === 403) {
                    logout();
                    return;
                }

                const data = await response.json();

                // Compute stats
                let totalRevenue = 0;
                let totalOrders = 0;

                // Build monthly sales table
                const monthlyTbody = document.getElementById('table-monthly-sales');
                if (data.monthly_sales && data.monthly_sales.length > 0) {
                    monthlyTbody.innerHTML = '';
                    data.monthly_sales.forEach(row => {
                        const rev = parseFloat(row.revenue);
                        totalRevenue += rev;
                        totalOrders += parseInt(row.orders_count);

                        monthlyTbody.innerHTML += `
                            <tr class="hover:bg-gray-900/10">
                                <td class="py-3.5 font-medium text-white">${row.month}</td>
                                <td class="py-3.5 text-right text-gray-300">${row.orders_count}</td>
                                <td class="py-3.5 text-right text-emerald-400 font-medium">$${rev.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                            </tr>
                        `;
                    });
                } else {
                    monthlyTbody.innerHTML = '<tr><td colspan="3" class="py-4 text-center text-gray-500">Chưa có dữ liệu đơn hàng.</td></tr>';
                }

                // Render Distribution table
                const distTbody = document.getElementById('table-service-dist');
                if (data.order_distribution_by_service_type && data.order_distribution_by_service_type.length > 0) {
                    distTbody.innerHTML = '';
                    data.order_distribution_by_service_type.forEach(row => {
                        distTbody.innerHTML += `
                            <tr class="hover:bg-gray-900/10">
                                <td class="py-3.5 font-medium text-white">${row.type}</td>
                                <td class="py-3.5 text-right text-gray-300">${row.orders_count}</td>
                                <td class="py-3.5 text-right text-indigo-400 font-medium">$${parseFloat(row.revenue).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                            </tr>
                        `;
                    });
                } else {
                    distTbody.innerHTML = '<tr><td colspan="3" class="py-4 text-center text-gray-500">Chưa có phân bổ dịch vụ.</td></tr>';
                }

                // Update metrics cards
                document.getElementById('metric-revenue').innerText = `$${totalRevenue.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                document.getElementById('metric-orders').innerText = totalOrders;

            } catch (err) {
                console.error(err);
            }
        }

        async function fetchOrders() {
            try {
                const response = await fetch('/api/orders?per_page=50', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const data = await response.json();
                const tbody = document.getElementById('table-orders-list');
                tbody.innerHTML = '';

                // Handle Cursor Paginator response structure
                const orders = data.data || [];

                if (orders.length > 0) {
                    orders.forEach(order => {
                        // Status styling
                        let statusBadge = '';
                        if (order.status === 'pending') statusBadge = '<span class="px-2 py-0.5 text-xs font-semibold rounded bg-yellow-900/40 text-yellow-300 border border-yellow-500/20">Chờ xử lý</span>';
                        else if (order.status === 'processing') statusBadge = '<span class="px-2 py-0.5 text-xs font-semibold rounded bg-indigo-900/40 text-indigo-300 border border-indigo-500/20">Đang chạy</span>';
                        else if (order.status === 'completed') statusBadge = '<span class="px-2 py-0.5 text-xs font-semibold rounded bg-emerald-900/40 text-emerald-300 border border-emerald-500/20">Hoàn thành</span>';
                        else statusBadge = '<span class="px-2 py-0.5 text-xs font-semibold rounded bg-red-900/40 text-red-300 border border-red-500/20">Đã hủy</span>';

                        const formattedDue = order.due_at ? new Date(order.due_at).toLocaleDateString() : 'N/A';

                        tbody.innerHTML += `
                            <tr class="hover:bg-gray-900/10">
                                <td class="px-6 py-4.5 font-medium text-white">${order.order_number}</td>
                                <td class="px-6 py-4.5 text-gray-300">${order.customer?.name || 'Khách vãng lai'}</td>
                                <td class="px-6 py-4.5">${statusBadge}</td>
                                <td class="px-6 py-4.5 text-right font-medium text-emerald-400">$${parseFloat(order.total_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                <td class="px-6 py-4.5 text-gray-400">${formattedDue}</td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-gray-500">Chưa có đơn hàng nào được tạo.</td></tr>';
                }
            } catch (err) {
                console.error(err);
            }
        }

        // ------------------ NEW ORDER TRANSACTION ------------------
        function openCreateOrderModal() {
            document.getElementById('modal-order').classList.remove('hidden');
        }

        function closeCreateOrderModal() {
            document.getElementById('modal-order').classList.add('hidden');
        }

        async function submitNewOrder(e) {
            e.preventDefault();
            const customerId = parseInt(document.getElementById('order-customer').value);
            
            // Build services array
            const services = [];
            for (let i = 1; i <= 3; i++) {
                const checkbox = document.getElementById(`svc-check-${i}`);
                if (checkbox.checked) {
                    services.push({
                        id: parseInt(checkbox.value),
                        quantity: parseInt(document.getElementById(`svc-qty-${i}`).value)
                    });
                }
            }

            if (services.length === 0) {
                alert('Vui lòng chọn ít nhất một dịch vụ để đặt hàng!');
                return;
            }

            try {
                const response = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        customer_id: customerId,
                        currency: 'USD',
                        services: services
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Lỗi khi tạo đơn hàng.');
                }

                closeCreateOrderModal();
                // Reset form check
                for (let i = 1; i <= 3; i++) {
                    document.getElementById(`svc-check-${i}`).checked = false;
                }
                
                // Show list
                switchTab('orders');
            } catch (err) {
                alert(err.message);
            }
        }

        // ------------------ AI CHAT / RETRIEVAL ------------------
        function usePresetPrompt(promptText) {
            document.getElementById('ai-query-input').value = promptText;
        }

        async function handleAIQuery(e) {
            e.preventDefault();
            const queryInput = document.getElementById('ai-query-input');
            const query = queryInput.value.trim();
            if (!query) return;

            const container = document.getElementById('ai-response-container');
            const loading = document.getElementById('ai-loading');

            // Add user bubble
            container.innerHTML += `
                <div class="flex justify-end">
                    <div class="max-w-[80%] p-3 bg-indigo-600 rounded-2xl rounded-tr-none text-sm text-white font-medium">
                        ${query}
                    </div>
                </div>
            `;
            
            queryInput.value = '';
            loading.classList.remove('hidden');

            try {
                const response = await fetch('/api/admin/ai/insights', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ query, provider: 'mock' })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Lỗi kết nối tới mô hình AI.');
                }

                // Render sources list
                let sourcesHtml = '';
                if (data.context && data.context.length > 0) {
                    sourcesHtml = '<div class="mt-3 pt-3 border-t border-indigo-500/20 text-[11px] text-gray-500 space-y-1"><strong>Dữ liệu tìm kiếm ngữ cảnh (RAG Context):</strong>';
                    data.context.forEach(src => {
                        sourcesHtml += `<div>📄 ${src.source} (Mức độ khớp: ${src.score})</div>`;
                    });
                    sourcesHtml += '</div>';
                }

                // Add AI bubble
                container.innerHTML += `
                    <div class="flex justify-start">
                        <div class="max-w-[85%] p-4 bg-gray-900/60 border border-gray-800 rounded-2xl rounded-tl-none text-sm text-gray-200">
                            <div class="font-semibold text-indigo-400 text-xs mb-1 uppercase tracking-wider">AI Copilot Analysis</div>
                            <p class="leading-relaxed">${data.insight}</p>
                            ${sourcesHtml}
                            <div class="mt-2 text-[10px] text-gray-500">Thời gian phản hồi: ${data.latency_ms} ms</div>
                        </div>
                    </div>
                `;

            } catch (err) {
                container.innerHTML += `
                    <div class="flex justify-start">
                        <div class="max-w-[80%] p-3 bg-red-900/20 border border-red-500/20 rounded-2xl rounded-tl-none text-sm text-red-300">
                            Lỗi: ${err.message}
                        </div>
                    </div>
                `;
            } finally {
                loading.classList.add('hidden');
                // Scroll container to bottom
                const chatBox = container.parentElement;
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }
    </script>
</body>
</html>
