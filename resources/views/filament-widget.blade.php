<x-filament-widgets::widget>
    <div style="
        position: relative; 
        overflow: hidden; 
        border-radius: 12px; 
        padding: 35px; 
        color: #ffffff; 
        min-height: 180px;
        background-image: linear-gradient(to right, rgba(9, 13, 22, 1) 0%, rgba(9, 13, 22, 0.85) 50%, rgba(0, 0, 0, 0.2) 100%), url('{{ asset('storage/images/bg-dashboard.webp') }}'); 
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #1e293b;
    ">
        
        <div style="
            position: relative; 
            z-index: 2; 
            display: flex; 
            flex-direction: row; 
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap;
            gap: 20px;
        ">
            
            <div style="flex: 1; min-width: 280px;">
                <span style="
                    display: inline-block;
                    font-size: 10px; 
                    text-transform: uppercase; 
                    letter-spacing: 1px; 
                    color: #fbbf24; 
                    background-color: rgba(255, 255, 255, 0.08); 
                    padding: 4px 10px; 
                    border-radius: 6px; 
                    margin-bottom: 12px;
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    font-weight: 600;
                ">
                    🏎️ ED Rent Executive Panel
                </span>
                
                <h2 style="
                    font-size: 24px; 
                    font-weight: 700; 
                    margin: 0 0 8px 0;
                    letter-spacing: -0.5px;
                    line-height: 1.2;
                ">
                    Selamat Datang Kembali, <span style="color: #ffffff;">{{ auth()->user()->name }}</span>.
                </h2>
                
                <p style="
                    font-size: 13.5px; 
                    color: #94a3b8; 
                    margin: 0; 
                    line-height: 1.6; 
                    max-width: 550px;
                ">
                    Sistem ED Rent siap dipantau. Seluruh data transaksi, ketersediaan unit motor, dan log aktivitas admin berjalan dengan optimal.
                </p>
            </div>
            
            <div style="
                display: flex; 
                flex-direction: column; 
                align-items: flex-end; 
                gap: 10px;
                min-width: 180px;
            ">
                <div style="
                    display: flex; 
                    align-items: center; 
                    gap: 8px; 
                    background-color: rgba(0, 0, 0, 0.5); 
                    backdrop-filter: blur(8px);
                    padding: 8px 14px; 
                    border-radius: 8px; 
                    font-size: 12px; 
                    color: #34d399; 
                    font-weight: 600;
                    border: 1px solid rgba(255, 255, 255, 0.05);
                ">
                    <span style="
                        display: inline-block; 
                        width: 8px; 
                        height: 8px; 
                        background-color: #10b981; 
                        border-radius: 50%;
                        box-shadow: 0 0 8px #10b981;
                    "></span>
                    System Active
                </div>
                
                <div style="
                    background-color: rgba(0, 0, 0, 0.5); 
                    backdrop-filter: blur(8px);
                    padding: 8px 14px; 
                    border-radius: 8px; 
                    font-size: 12px; 
                    color: #94a3b8; 
                    font-family: sans-serif;
                    border: 1px solid rgba(255, 255, 255, 0.05);
                ">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>

        </div>
    </div>
</x-filament-widgets::widget>