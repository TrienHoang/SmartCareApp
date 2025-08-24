<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
            size: A4 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background-color: #fff;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #1e40af;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header .subtitle {
            color: #64748b;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .metadata {
            width: 100%;
            margin-top: 20px;
        }

        .metadata table {
            width: 100%;
            border-collapse: collapse;
        }

        .metadata td {
            font-size: 11px;
            color: #6b7280;
            padding: 5px;
            text-align: center;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #3b82f6;
            color: white;
            padding: 12px 15px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stats-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .stats-row {
            width: 100%;
            margin-bottom: 15px;
        }

        .stats-row table {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-row td {
            width: 25%;
            padding: 0 5px;
            vertical-align: top;
        }

        .stat-card {
            border: 2px solid #e5e7eb;
            border-top: 4px solid #10b981;
            padding: 15px;
            text-align: center;
            background: #f8fafc;
            height: 100px;
            position: relative;
        }

        .stat-card.revenue {
            border-top-color: #f59e0b;
        }

        .stat-card.appointments {
            border-top-color: #3b82f6;
        }

        .stat-card.patients {
            border-top-color: #8b5cf6;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
            display: block;
        }

        .stat-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .growth-indicator {
            margin-top: 8px;
            font-size: 10px;
            font-weight: bold;
        }

        .growth-positive {
            color: #059669;
        }

        .growth-negative {
            color: #dc2626;
        }

        .table-container {
            margin-bottom: 20px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border: 1px solid #d1d5db;
        }

        th {
            background: #374151;
            color: white;
            padding: 12px 8px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
            border: 1px solid #4b5563;
        }

        td {
            padding: 10px 8px;
            border: 1px solid #e5e7eb;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .highlight-row {
            background-color: #fef3c7 !important;
            font-weight: bold;
        }

        .two-column-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .two-column-table {
            width: 100%;
            border-collapse: collapse;
        }

        .two-column-table td {
            width: 50%;
            padding: 0 10px;
            vertical-align: top;
        }

        .performance-container {
            width: 100%;
        }

        .performance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .performance-table td {
            width: 50%;
            padding: 5px;
        }

        .metric-card {
            background: white;
            border: 2px solid #e5e7eb;
            padding: 15px;
            text-align: center;
            height: 80px;
        }

        .metric-value {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .metric-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .excellent { color: #059669; }
        .good { color: #0891b2; }
        .warning { color: #d97706; }
        .critical { color: #dc2626; }

        .summary-box {
            background: #f0f9ff;
            border: 2px solid #7dd3fc;
            padding: 20px;
            margin-top: 20px;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #0369a1;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .summary-content {
            font-size: 11px;
            line-height: 1.6;
            color: #374151;
        }

        .summary-content ul {
            margin-left: 20px;
            margin-top: 8px;
        }

        .summary-content li {
            margin-bottom: 4px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }

        .signature-section {
            margin-top: 50px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            padding: 20px;
            vertical-align: top;
        }

        .signature-box {
            border-top: 2px solid #d1d5db;
            padding-top: 10px;
            height: 80px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-completed { 
            background: #d1fae5; 
            color: #065f46; 
            border: 1px solid #10b981;
        }
        
        .status-pending { 
            background: #fef3c7; 
            color: #92400e; 
            border: 1px solid #f59e0b;
        }
        
        .status-cancelled { 
            background: #fee2e2; 
            color: #991b1b; 
            border: 1px solid #ef4444;
        }
        
        .status-confirmed { 
            background: #dbeafe; 
            color: #1e40af; 
            border: 1px solid #3b82f6;
        }

        .page-break {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>BAO CAO THONG KE HE THONG</h1>
        <div class="subtitle">He thong Quan ly Phong kham Truc tuyen</div>
        <div class="metadata">
            <table>
                <tr>
                    <td><strong>Ky bao cao:</strong> {{ $period }}</td>
                    <td><strong>Ngay tao:</strong> {{ $generated_at }}</td>
                    <td><strong>Ma bao cao:</strong> RPT-{{ date('YmdHis') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Tong quan he thong -->
    <div class="section no-break">
        <div class="section-title">TONG QUAN HE THONG</div>
        
        <div class="stats-container">
            <div class="stats-row">
                <table>
                    <tr>
                        <td>
                            <div class="stat-card revenue">
                                <div class="stat-value">{{ number_format($globalStat->total_revenue, 0, ',', '.') }}</div>
                                <div class="stat-label">Tong doanh thu (VND)</div>
                            </div>
                        </td>
                        <td>
                            <div class="stat-card appointments">
                                <div class="stat-value">{{ number_format($globalStat->total_appointments) }}</div>
                                <div class="stat-label">Tong lich hen</div>
                            </div>
                        </td>
                        <td>
                            <div class="stat-card patients">
                                <div class="stat-value">{{ number_format($globalStat->total_patients) }}</div>
                                <div class="stat-label">Tong benh nhan</div>
                            </div>
                        </td>
                        <td>
                            <div class="stat-card">
                                <div class="stat-value">{{ number_format($globalStat->total_doctors) }}</div>
                                <div class="stat-label">Tong bac si</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Thong ke theo trang thai -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 25%;">Trang thai lich hen</th>
                        <th style="width: 15%;">So luong</th>
                        <th style="width: 15%;">Ty le (%)</th>
                        <th style="width: 45%;">Ghi chu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="status-badge status-completed">HOAN THANH</span></td>
                        <td>{{ number_format($globalStat->appointments_completed) }}</td>
                        <td>{{ $globalStat->total_appointments > 0 ? round(($globalStat->appointments_completed / $globalStat->total_appointments) * 100, 1) : 0 }}%</td>
                        <td>Lich hen da hoan thanh kham</td>
                    </tr>
                    <tr>
                        <td><span class="status-badge status-confirmed">DA XAC NHAN</span></td>
                        <td>{{ number_format($globalStat->appointments_confirmed) }}</td>
                        <td>{{ $globalStat->total_appointments > 0 ? round(($globalStat->appointments_confirmed / $globalStat->total_appointments) * 100, 1) : 0 }}%</td>
                        <td>Lich hen da duoc xac nhan</td>
                    </tr>
                    <tr>
                        <td><span class="status-badge status-pending">CHO XU LY</span></td>
                        <td>{{ number_format($globalStat->appointments_pending) }}</td>
                        <td>{{ $globalStat->total_appointments > 0 ? round(($globalStat->appointments_pending / $globalStat->total_appointments) * 100, 1) : 0 }}%</td>
                        <td>Lich hen dang cho xac nhan</td>
                    </tr>
                    <tr>
                        <td><span class="status-badge status-cancelled">DA HUY</span></td>
                        <td>{{ number_format($globalStat->appointments_cancelled) }}</td>
                        <td>{{ $globalStat->total_appointments > 0 ? round(($globalStat->appointments_cancelled / $globalStat->total_appointments) * 100, 1) : 0 }}%</td>
                        <td>Lich hen bi huy boi benh nhan/he thong</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Phan tich tang truong -->
    <div class="section no-break">
        <div class="section-title">PHAN TICH TANG TRUONG</div>
        
        <div class="two-column-container">
            <table class="two-column-table">
                <tr>
                    <td>
                        <div class="stat-card">
                            <div class="stat-value">{{ number_format($bookingCurrent) }}</div>
                            <div class="stat-label">Luot dat lich thang nay</div>
                            <div class="growth-indicator {{ $bookingGrowthValue >= 0 ? 'growth-positive' : 'growth-negative' }}">
                                {{ $bookingGrowthValue >= 0 ? '+' : '' }}{{ $bookingGrowthValue }}% {{ $bookingGrowthLabel }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-card revenue">
                            <div class="stat-value">{{ number_format($revenueCurrent, 0, ',', '.') }}</div>
                            <div class="stat-label">Doanh thu thang nay (VND)</div>
                            <div class="growth-indicator {{ $revenueGrowthValue >= 0 ? 'growth-positive' : 'growth-negative' }}">
                                {{ $revenueGrowthValue >= 0 ? '+' : '' }}{{ $revenueGrowthValue }}% {{ $revenueGrowthLabel }}
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Hieu suat hoat dong -->
    <div class="section no-break">
        <div class="section-title">HIEU SUAT HOAT DONG</div>
        
        <div class="performance-container">
            <table class="performance-table">
                <tr>
                    <td>
                        <div class="metric-card">
                            <div class="metric-value {{ $performanceStats['completed_rate'] >= 80 ? 'excellent' : ($performanceStats['completed_rate'] >= 60 ? 'good' : 'warning') }}">
                                {{ $performanceStats['completed_rate'] }}%
                            </div>
                            <div class="metric-label">Ty le hoan thanh</div>
                        </div>
                    </td>
                    <td>
                        <div class="metric-card">
                            <div class="metric-value {{ $performanceStats['cancel_rate'] <= 10 ? 'excellent' : ($performanceStats['cancel_rate'] <= 20 ? 'good' : 'critical') }}">
                                {{ $performanceStats['cancel_rate'] }}%
                            </div>
                            <div class="metric-label">Ty le huy lich</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        @if(isset($performanceStats['avg_waiting_time']))
                        <div class="metric-card">
                            <div class="metric-value {{ $performanceStats['avg_waiting_time'] <= 10 ? 'excellent' : ($performanceStats['avg_waiting_time'] <= 20 ? 'good' : 'warning') }}">
                                {{ $performanceStats['avg_waiting_time'] }} phut
                            </div>
                            <div class="metric-label">Thoi gian cho TB</div>
                        </div>
                        @endif
                    </td>
                    <td>
                        <div class="metric-card">
                            <div class="metric-value {{ $patientStats['return_rate'] >= 30 ? 'excellent' : ($patientStats['return_rate'] >= 20 ? 'good' : 'warning') }}">
                                {{ $patientStats['return_rate'] }}%
                            </div>
                            <div class="metric-label">Ty le tai kham</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Page break -->
    <div class="page-break"></div>

    <!-- Top dich vu -->
    <div class="section no-break">
        <div class="section-title">TOP DICH VU PHO BIEN</div>
        
        @if($topService)
        <div class="summary-box">
            <div class="summary-title">Dich vu duoc ua chuong nhat</div>
            <div class="summary-content">
                <strong>{{ $topService->name }}</strong> voi <strong>{{ $topService->bookings }}</strong> luot dat lich trong nam nay, 
                chiem {{ round(($topService->bookings / $serviceStats->sum('bookings')) * 100, 1) }}% tong so booking.
            </div>
        </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">STT</th>
                        <th style="width: 40%;">Ten dich vu</th>
                        <th style="width: 15%;">So luot dat</th>
                        <th style="width: 12%;">Ty le (%)</th>
                        <th style="width: 25%;">Xu huong</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceStats as $index => $service)
                    <tr class="{{ $index === 0 ? 'highlight-row' : '' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ number_format($service->bookings) }}</td>
                        <td>{{ round(($service->bookings / $serviceStats->sum('bookings')) * 100, 1) }}%</td>
                        <td>
                            @if($index < 3)
                                <span class="status-badge status-completed">TANG MANH</span>
                            @else
                                <span class="status-badge status-confirmed">ON DINH</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top bac si -->
    <div class="section no-break">
        <div class="section-title">HIEU SUAT BAC SI</div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 6%;">STT</th>
                        <th style="width: 20%;">Ho ten</th>
                        <th style="width: 18%;">Chuyen khoa</th>
                        <th style="width: 12%;">Tong lich hen</th>
                        <th style="width: 12%;">Hoan thanh</th>
                        <th style="width: 12%;">Danh gia TB</th>
                        <th style="width: 20%;">Hieu suat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctorStats as $index => $doctor)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $doctor['name'] }}</td>
                        <td>{{ $doctor['specialization'] }}</td>
                        <td>{{ number_format($doctor['total_appointments']) }}</td>
                        <td>{{ number_format($doctor['completed_appointments']) }}</td>
                        <td>{{ $doctor['average_rating'] }}/5.0</td>
                        <td>
                            @php
                                $completionRate = $doctor['total_appointments'] > 0 
                                    ? round(($doctor['completed_appointments'] / $doctor['total_appointments']) * 100, 1) 
                                    : 0;
                            @endphp
                            <span class="status-badge {{ $completionRate >= 90 ? 'status-completed' : ($completionRate >= 75 ? 'status-confirmed' : 'status-pending') }}">
                                {{ $completionRate }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Thong tin benh nhan -->
    <div class="section no-break">
        <div class="section-title">THONG TIN BENH NHAN</div>
        
        <div class="stats-container">
            <div class="stats-row">
                <table>
                    <tr>
                        <td>
                            <div class="stat-card patients">
                                <div class="stat-value">{{ number_format($patientStats['total_patients']) }}</div>
                                <div class="stat-label">Tong benh nhan</div>
                            </div>
                        </td>
                        <td>
                            <div class="stat-card">
                                <div class="stat-value">{{ number_format($patientStats['new_this_week']) }}</div>
                                <div class="stat-label">BN moi tuan nay</div>
                            </div>
                        </td>
                        <td>
                            <div class="stat-card">
                                <div class="stat-value">{{ $patientStats['return_rate'] }}%</div>
                                <div class="stat-label">Ty le tai kham</div>
                            </div>
                        </td>
                        <td>
                            <div class="stat-card">
                                <div class="stat-value">{{ $patientStats['total_patients'] > 0 ? round(($patientStats['new_this_week'] / $patientStats['total_patients']) * 100, 1) : 0 }}%</div>
                                <div class="stat-label">Ty le BN moi</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Tom tat va khuyen nghi -->
    <div class="section no-break">
        <div class="section-title">TOM TAT & KHUYEN NGHI</div>
        
        <div class="summary-box">
            <div class="summary-title">Diem noi bat</div>
            <div class="summary-content">
                <p><strong>Tich cuc:</strong></p>
                <ul>
                    @if($revenueGrowthValue > 0)
                    <li>Doanh thu tang {{ $revenueGrowthValue }}% so voi thang truoc</li>
                    @endif
                    @if($performanceStats['completed_rate'] >= 80)
                    <li>Ty le hoan thanh lich hen dat {{ $performanceStats['completed_rate'] }}% (xuat sac)</li>
                    @endif
                    @if($patientStats['return_rate'] >= 25)
                    <li>Ty le tai kham {{ $patientStats['return_rate'] }}% the hien su hai long cua benh nhan</li>
                    @endif
                </ul>
                
                <p style="margin-top: 15px;"><strong>Can cai thien:</strong></p>
                <ul>
                    @if($performanceStats['cancel_rate'] > 15)
                    <li>Ty le huy lich {{ $performanceStats['cancel_rate'] }}% can duoc giam thieu</li>
                    @endif
                    @if(isset($performanceStats['avg_waiting_time']) && $performanceStats['avg_waiting_time'] > 20)
                    <li>Thoi gian cho trung binh {{ $performanceStats['avg_waiting_time'] }} phut can duoc toi uu</li>
                    @endif
                    @if($bookingGrowthValue < 0)
                    <li>Luot dat lich giam {{ abs($bookingGrowthValue) }}% can co bien phap kich thich</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Chu ky -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-box">
                        <strong>Nguoi lap bao cao</strong><br><br><br>
                        <div style="border-top: 2px solid #000; padding-top: 8px; margin-top: 30px;">
                            Truong phong IT
                        </div>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        <strong>Nguoi phe duyet</strong><br><br><br>
                        <div style="border-top: 2px solid #000; padding-top: 8px; margin-top: 30px;">
                            Giam doc Y khoa
                        </div>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        <strong>Phe duyet cuoi</strong><br><br><br>
                        <div style="border-top: 2px solid #000; padding-top: 8px; margin-top: 30px;">
                            Tong Giam doc
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Bao cao duoc tao tu dong boi he thong quan ly phong kham • Bao mat cao • Chi danh cho noi bo</strong></p>
        <p>© 2024 Healthcare Management System. All rights reserved.</p>
    </div>
</body>
</html>