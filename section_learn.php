<?php 
include 'data_zoo.php'; 
$id = $_GET['id'] ?? 'c1s1';
$content = $courses[$id] ?? $courses['c1s1'];
$slides = $content['ppt'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $content['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        /* 关键修复：使用 100dvh 适应移动端动态高度 */
        body { background: #e0f7fa; font-family: 'Segoe UI', sans-serif; height: 100vh; height: 100dvh; overflow: hidden; display: flex; flex-direction: column; }
        
        /* Stage 占据剩余空间，但留出边距 */
        .stage { flex: 1; display: flex; align-items: center; justify-content: center; background: linear-gradient(to bottom, #b3e5fc, #fff); overflow: hidden; width: 100%; padding: 10px; }
        
        /* 卡片自适应：不再强制 85vh，而是填满 Stage 区域，预留底部导航栏空间 */
        .slide-card { 
            width: 100%; 
            max-width: 960px; 
            height: 100%; 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            display: none; 
            flex-direction: column; 
            overflow: hidden; 
            border: 1px solid #b3e5fc; 
            position: relative; 
        }
        .slide-card.active { display: flex; }
        
        .role-header { padding: 20px 30px; color: white; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .role-header h2 { font-size: 1.5rem; }
        .role-avatar { font-size: 3rem; filter: drop-shadow(2px 2px 0 rgba(0,0,0,0.2)); }
        
        .content-body { padding: 30px 50px; flex: 1; overflow-y: auto; font-size: 1.4rem; color: #333; line-height: 1.6; }
        
        /* 底部导航栏：固定在底部，防止被挤出 */
        .nav-bar { background: white; padding: 15px; text-align: center; border-top: 1px solid #eee; flex-shrink: 0; padding-bottom: max(15px, env(safe-area-inset-bottom)); z-index: 100; }
        
        /* === 视觉素材库 === */
        .visual-box { margin: 20px auto; text-align: center; height: 350px; display:flex; align-items:center; justify-content:center; overflow:hidden; border-radius:15px; background:#f8f9fa; position:relative; width: 100%; }
        .icon-large { font-size: 8rem; animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }

        /* 移动端适配 CSS：媒体查询 */
        @media (max-width: 768px) {
            .role-header { padding: 15px 20px; }
            .role-header h2 { font-size: 1.2rem; }
            .role-avatar { font-size: 2rem; }
            .content-body { padding: 15px 20px; font-size: 1.1rem; }
            .visual-box { height: 250px; margin: 10px auto; }
            .icon-large { font-size: 5rem; }
            
            /* 缩小大型 CSS 动画图示，防止在手机上溢出 */
            .css-solar, .css-thermal, .css-water-cycle, .css-typhoon, .css-flood, .css-gnss-pin { transform: scale(0.65); transform-origin: center; }
            .css-karst, .css-river-valley, .css-dune, .css-coast, .css-veg-layer, .css-mangrove { transform: scale(0.7); transform-origin: center; }
        }

        /* 原有 CSS 动画保持不变，但增加 max-width 防止撑破 */
        .css-solar { width:300px; height:300px; position:relative; max-width: 100%; }
        .sun { width:50px; height:50px; background:gold; border-radius:50%; position:absolute; top:125px; left:125px; box-shadow:0 0 30px gold; animation: pulse 2s infinite; }
        .orbit { border:1px solid #ccc; border-radius:50%; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); animation: spin 6s linear infinite; }
        .planet { width:15px; height:15px; background:blue; border-radius:50%; position:absolute; top:-7px; left:50%; margin-left:-7px; }
        @keyframes spin { 100% { transform:translate(-50%,-50%) rotate(360deg); } }

        .css-thermal { width:300px; height:200px; border-bottom:5px solid #555; position:relative; max-width: 100%; }
        .arrow { font-size:30px; position:absolute; font-weight:bold; }
        .a-up { color:red; left:40px; animation: up 2s infinite; }
        .a-down { color:blue; right:40px; animation: down 2s infinite; }
        .a-flow { color:#555; top:20px; left:130px; animation: right 2s infinite; }
        @keyframes up { 0%{bottom:0;opacity:0} 50%{bottom:120px;opacity:1} 100%{bottom:120px;opacity:0} }
        @keyframes down { 0%{top:20px;opacity:0} 50%{top:140px;opacity:1} 100%{top:140px;opacity:0} }
        @keyframes right { 0%{transform:translateX(-20px);opacity:0} 50%{transform:translateX(0);opacity:1} 100%{transform:translateX(20px);opacity:0} }

        .css-water-cycle { width:400px; height:300px; position:relative; overflow:hidden; max-width: 100%; }
        .ocean { width:100%; height:50px; background:#2980b9; position:absolute; bottom:0; }
        .land { width:150px; height:100px; background:#27ae60; position:absolute; bottom:0; right:0; border-radius:50px 0 0 0; }
        .vapour { font-size:40px; position:absolute; left:50px; animation: rise 3s infinite; }
        .rain { font-size:40px; position:absolute; right:50px; top:50px; animation: fall 1s infinite; color:#3498db; }
        @keyframes rise { 0%{bottom:50px;opacity:1} 100%{bottom:200px;opacity:0} }
        @keyframes fall { 0%{top:50px;opacity:0} 50%{top:150px;opacity:1} }

        .css-karst { width: 300px; height: 150px; position: relative; border-bottom: 5px solid #7f8c8d; max-width: 100%; }
        .peak { position: absolute; bottom: 0; width: 0; height: 0; border-left: 30px solid transparent; border-right: 30px solid transparent; border-bottom: 80px solid #95a5a6; }
        .peak:nth-child(1) { left: 20px; border-bottom-width: 100px; border-bottom-color: #7f8c8d; z-index: 2; }
        .peak:nth-child(2) { left: 60px; border-bottom-width: 140px; z-index: 1; }
        .peak:nth-child(3) { left: 120px; border-bottom-width: 90px; border-bottom-color: #7f8c8d; z-index: 3; }
        .peak:nth-child(4) { left: 180px; border-bottom-width: 120px; z-index: 1; }

        .css-river-valley { width: 300px; height: 200px; background: #a5d6a7; position: relative; overflow: hidden; max-width: 100%; }
        .river-path { width: 400px; height: 100px; border: 20px solid #3498db; border-radius: 50%; position: absolute; top: 50px; left: -50px; transform: rotate(10deg); }
        .river-path::after { content:''; position: absolute; top: 20px; left: 100px; width: 20px; height: 20px; background: #fff; border-radius: 50%; } 
        
        .css-coast { width: 300px; height: 150px; background: #f1c40f; position: relative; overflow: hidden; max-width: 100%; }
        .coast-water { width: 100%; height: 80px; background: #2980b9; position: absolute; bottom: 0; animation: tide 3s infinite ease-in-out;}
        @keyframes tide { 0%,100%{height: 80px} 50%{height: 60px} }

        .css-dune { width: 300px; height: 150px; position: relative; max-width: 100%; }
        .dune-shape { width: 200px; height: 100px; background: #e67e22; border-radius: 100px 100px 0 0; position: absolute; bottom: 0; left: 50px; box-shadow: inset -20px 0 0 rgba(0,0,0,0.1); }
        .wind-arrow { font-size: 30px; position: absolute; top: 20px; left: 20px; animation: windMove 2s infinite; }
        @keyframes windMove { 0%{transform: translateX(0); opacity: 0;} 50%{opacity: 1;} 100%{transform: translateX(50px); opacity: 0;} }

        .css-slope-tri { width: 0; height: 0; border-bottom: 150px solid #34495e; border-right: 250px solid transparent; position: relative; margin-top: 50px; }
        .slope-text { position: absolute; top: 60px; left: 50px; color: white; font-weight: bold; transform: rotate(-30deg); }

        .css-zoom { width: 100px; height: 100px; border: 10px solid #34495e; border-radius: 50%; position: relative; }
        .css-zoom::after { content:''; width: 20px; height: 80px; background: #34495e; position: absolute; bottom: -60px; right: -40px; transform: rotate(-45deg); }
        .zoom-target { width: 50px; height: 50px; background: #e74c3c; border-radius: 50%; position: absolute; top: 15px; left: 15px; animation: pulse 1s infinite; }
        
        .css-height-diff { display: flex; align-items: flex-end; justify-content: center; height: 200px; gap: 20px; }
        .h-bar-1 { width: 50px; height: 100px; background: #95a5a6; }
        .h-bar-2 { width: 50px; height: 180px; background: #2c3e50; position: relative; }
        .h-line { position: absolute; top: 0; left: -70px; width: 120px; border-top: 2px dashed red; }

        .css-veg-layer { width: 300px; height: 200px; position: relative; border-bottom: 5px solid #795548; background: linear-gradient(to bottom, #e1f5fe 0%, #fff 80%); max-width: 100%; }
        .tree-high { font-size: 60px; position: absolute; bottom: 0; left: 20px; color: #2e7d32; }
        .tree-mid { font-size: 40px; position: absolute; bottom: 0; left: 100px; color: #43a047; }
        .tree-low { font-size: 20px; position: absolute; bottom: 0; left: 180px; color: #66bb6a; }
        .grass { font-size: 15px; position: absolute; bottom: 0; right: 20px; color: #81c784; }

        .css-mangrove { width: 300px; height: 180px; position: relative; overflow: hidden; max-width: 100%; }
        .mangrove-water { width: 100%; height: 60px; background: #81d4fa; position: absolute; bottom: 0; opacity: 0.7; }
        .mangrove-roots { width: 100%; height: 80px; position: absolute; bottom: 0; background-image: radial-gradient(circle, transparent 40%, #5d4037 41%); background-size: 30px 40px; background-position: 0 10px; }
        .mangrove-tree { font-size: 80px; position: absolute; bottom: 40px; left: 100px; }

        .css-soil-texture { display: flex; gap: 15px; align-items: flex-end; height: 150px; }
        .soil-pile { width: 60px; border-radius: 50% 50% 5px 5px; position: relative; }
        .pile-sand { height: 60px; background: #fff3e0; border: 2px solid #ffe0b2; }
        .pile-loam { height: 80px; background: #8d6e63; border: 2px solid #5d4037; }
        .pile-clay { height: 60px; background: #bcaaa4; border: 2px solid #795548; }

        .css-soil-profile { width: 120px; height: 200px; border: 2px solid #333; display: flex; flex-direction: column; font-size: 10px; color: white; text-align: center; }
        .profile-o { height: 10%; background: #333; display:flex;align-items:center;justify-content:center; }
        .profile-a { height: 20%; background: #212121; display:flex;align-items:center;justify-content:center; }
        .profile-e { height: 15%; background: #bdbdbd; color:#333; display:flex;align-items:center;justify-content:center; }
        .profile-b { height: 25%; background: #795548; display:flex;align-items:center;justify-content:center; }
        .profile-c { height: 20%; background: #ffe0b2; color:#333; display:flex;align-items:center;justify-content:center; }
        .profile-r { height: 10%; background: #607d8b; display:flex;align-items:center;justify-content:center; }
        
        .css-forest-types { display: flex; justify-content: space-around; width: 100%; align-items: flex-end; height: 150px; }
        .f-type-rain { font-size: 60px; filter: drop-shadow(2px 2px 0 #1b5e20); }
        .f-type-needle { font-size: 50px; filter: grayscale(0.5); }

        .css-typhoon { width: 300px; height: 300px; position: relative; display: flex; align-items: center; justify-content: center; max-width: 100%; }
        .typhoon-eye { width: 30px; height: 30px; background: white; border-radius: 50%; z-index: 10; box-shadow: 0 0 10px rgba(0,0,0,0.5); }
        .typhoon-arm { position: absolute; width: 100%; height: 100%; border-radius: 50%; border: 15px solid transparent; border-top-color: #ecf0f1; border-right-color: #bdc3c7; animation: spin 2s linear infinite; }
        .typhoon-arm:nth-child(2) { width: 70%; height: 70%; animation-duration: 1.5s; border-top-color: #95a5a6; }
        .typhoon-arm:nth-child(3) { width: 40%; height: 40%; animation-duration: 1s; border-top-color: #7f8c8d; }

        .css-flood { width: 300px; height: 200px; position: relative; overflow: hidden; background: #e0f7fa; border-bottom: 5px solid #0277bd; max-width: 100%; }
        .house-sub { width: 50px; height: 50px; background: #795548; position: absolute; bottom: 0; left: 50px; }
        .house-roof { width: 0; height: 0; border-left: 35px solid transparent; border-right: 35px solid transparent; border-bottom: 40px solid #a1887f; position: absolute; bottom: 50px; left: 40px; }
        .water-level { width: 100%; height: 60%; background: rgba(3, 169, 244, 0.7); position: absolute; bottom: 0; animation: rise 3s infinite alternate; }
        @keyframes rise { 0%{height: 20%} 100%{height: 80%} }

        .css-seismic-wave { width: 300px; height: 150px; display: flex; align-items: center; justify-content: center; gap: 5px; max-width: 100%; }
        .wave-bar { width: 10px; background: #e74c3c; animation: quake 1s infinite; }
        @keyframes quake { 0%,100%{height: 10px} 50%{height: 100px} }
        .wave-bar:nth-child(odd) { animation-delay: 0.1s; background: #c0392b; }
        
        .css-rs-scan { width: 250px; height: 200px; background: url('https://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/World_map_blank_without_borders.svg/300px-World_map_blank_without_borders.svg.png'); background-size: cover; position: relative; overflow: hidden; border: 2px solid #333; }
        .scan-line { width: 100%; height: 2px; background: red; position: absolute; top: 0; box-shadow: 0 0 10px red; animation: scan 2s infinite linear; }
        @keyframes scan { 0%{top:0} 100%{top:100%} }
        
        .css-gis-layer { width: 200px; height: 200px; position: relative; transform: rotateX(60deg) rotateZ(-30deg); transform-style: preserve-3d; }
        .layer-plate { width: 100%; height: 100%; position: absolute; border: 2px solid #333; background: rgba(255,255,255,0.8); transition: 0.5s; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .layer-1 { transform: translateZ(0px); background: rgba(46, 204, 113, 0.5); }
        .layer-2 { transform: translateZ(40px); background: rgba(52, 152, 219, 0.5); animation: floatLayer 2s infinite alternate; }
        .layer-3 { transform: translateZ(80px); background: rgba(231, 76, 60, 0.5); animation: floatLayer 2s infinite alternate-reverse; }
        @keyframes floatLayer { from{transform: translateZ(40px)} to{transform: translateZ(60px)} }

        .css-gnss-pin { width: 300px; height: 200px; position: relative; background: #f0f3f4; max-width: 100%; }
        .pin { font-size: 50px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -100%); color: #e74c3c; animation: bounce 1s infinite; }
        .sat { font-size: 30px; position: absolute; top: 20px; right: 20px; animation: orbit 3s infinite linear; }
                       
        .css-nuclear { font-size:3rem; font-weight:bold; color:#e67e22; animation: pulse 1s infinite; }
        .css-seismic { width:300px; height:100px; background: repeating-linear-gradient(90deg, #333, #333 2px, transparent 2px, transparent 20px); animation: shake 0.5s infinite; max-width: 100%; }
        @keyframes shake { 0%{transform:translateX(0)} 25%{transform:translateX(5px)} 75%{transform:translateX(-5px)} 100%{transform:translateX(0)} }
        
        .web-img { height: 100%; width: auto; object-fit: contain; max-width: 100%; }
    </style>
</head>
<body>
    <div class="stage">
        <?php foreach($slides as $k => $s): ?>
        <?php 
            $color = '#3498db'; $emoji = '🐰'; $name = '朱迪警官';
            if($s['role']=='nick') { $color='#e67e22'; $emoji='🦊'; $name='尼克'; }
            if($s['role']=='flash') { $color='#27ae60'; $emoji='🦥'; $name='闪电'; }
            if($s['role']=='bogo') { $color='#2c3e50'; $emoji='🐃'; $name='牛局长'; }
        ?>
        <div class="slide-card animate__animated animate__<?php echo $s['anim_type'] ?? 'fadeIn'; ?>" id="slide-<?php echo $k; ?>">
            <div class="role-header" style="background: <?php echo $color; ?>">
                <div><h2 class="m-0 fw-bold"><?php echo $s['title']; ?></h2><span style="opacity:0.9"><?php echo $name; ?></span></div>
                <div class="role-avatar"><?php echo $emoji; ?></div>
            </div>
            <div class="content-body">
                <?php echo $s['content']; ?>
                <div class="visual-box">
                    <?php 
                        $v = $s['visual'] ?? 'icon_star';
                        // CSS 动画代码保持不变...
                        if ($v == 'css_solar_system') echo '<div class="css-solar"><div class="sun"></div><div class="orbit" style="width:200px;height:200px"><div class="planet"></div></div></div>';
                        elseif ($v == 'css_thermal') echo '<div class="css-thermal"><div class="arrow a-up">🔥</div><div class="arrow a-down">❄️</div><div class="arrow a-flow">➡️</div></div>';
                        elseif ($v == 'css_water_cycle') echo '<div class="css-water-cycle"><div class="ocean"></div><div class="land"></div><div class="vapour">♨️</div><div class="rain">💧</div></div>';
                        elseif ($v == 'css_nuclear') echo '<div class="css-nuclear">H+H 💥 He</div>';
                        elseif ($v == 'css_seismic') echo '<div class="css-seismic"></div>';
                        elseif ($v == 'css_karst') echo '<div class="css-karst"><div class="peak"></div><div class="peak"></div><div class="peak"></div><div class="peak"></div></div>';
                        elseif ($v == 'css_river_valley') echo '<div class="css-river-valley"><div class="river-path"></div></div>';
                        elseif ($v == 'css_dune') echo '<div class="css-dune"><div class="wind-arrow">💨</div><div class="dune-shape"></div></div>';
                        elseif ($v == 'css_coast') echo '<div class="css-coast"><div class="coast-water"></div></div>';
                        elseif ($v == 'css_slope_tri') echo '<div class="css-slope-tri"><div class="slope-text">Slope</div></div>';
                        elseif ($v == 'css_zoom') echo '<div class="css-zoom"><div class="zoom-target"></div></div>';
                        elseif ($v == 'css_height_diff') echo '<div class="css-height-diff"><div class="h-bar-1"></div><div class="h-bar-2"><div class="h-line"></div></div></div>';
                        elseif ($v == 'css_veg_layer') echo '<div class="css-veg-layer"><div class="tree-high">🌳</div><div class="tree-mid">🌲</div><div class="tree-low">🌿</div><div class="grass">🌱</div></div>';
                        elseif ($v == 'css_mangrove') echo '<div class="css-mangrove"><div class="mangrove-roots"></div><div class="mangrove-water"></div><div class="mangrove-tree">🌳</div></div>';
                        elseif ($v == 'css_soil_texture') echo '<div class="css-soil-texture"><div class="soil-pile pile-sand" title="砂土"></div><div class="soil-pile pile-loam" title="壤土"></div><div class="soil-pile pile-clay" title="黏土"></div></div>';
                        elseif ($v == 'css_soil_profile') echo '<div class="css-soil-profile"><div class="profile-o">O</div><div class="profile-a">A</div><div class="profile-e">E</div><div class="profile-b">B</div><div class="profile-c">C</div><div class="profile-r">R</div></div>';
                        elseif ($v == 'css_forest_types') echo '<div class="css-forest-types"><div class="f-type-rain">🌴<br><span style="font-size:12px;color:#333">雨林</span></div><div class="f-type-needle">🌲<br><span style="font-size:12px;color:#333">针叶</span></div></div>';
                        elseif ($v == 'css_rainforest') echo '<div class="icon-large">🌴</div>';
                        elseif ($v == 'css_cactus') echo '<div class="icon-large">🌵</div>';
                        elseif ($v == 'css_black_soil') echo '<div style="width:100px;height:100px;background:#212121;border-radius:50%;box-shadow:0 0 20px #000;"></div>';
                        elseif ($v == 'css_typhoon') echo '<div class="css-typhoon"><div class="typhoon-eye"></div><div class="typhoon-arm"></div><div class="typhoon-arm"></div><div class="typhoon-arm"></div></div>';
                        elseif ($v == 'css_flood') echo '<div class="css-flood"><div class="house-sub"><div class="house-roof"></div></div><div class="water-level"></div></div>';
                        elseif ($v == 'css_seismic_wave') echo '<div class="css-seismic-wave"><div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div></div>';
                        elseif ($v == 'css_rs_scan') echo '<div class="css-rs-scan"><div class="scan-line"></div></div>';
                        elseif ($v == 'css_gis_layer') echo '<div class="css-gis-layer"><div class="layer-plate layer-1">地形</div><div class="layer-plate layer-2">水系</div><div class="layer-plate layer-3">人口</div></div>';
                        elseif ($v == 'css_gnss_pin') echo '<div class="css-gnss-pin"><div class="pin">📍</div><div class="sat">🛰️</div></div>';
                        
                        elseif ($v == 'css_landslide') echo '<div class="icon-large" style="transform:rotate(45deg)">🏔️↘️</div>';
                        elseif ($v == 'css_debris_flow') echo '<div class="icon-large">🌊🪨</div>';
                        elseif ($v == 'css_quake_safe') echo '<div class="icon-large">🙆‍♂️📐</div>';
                        elseif ($v == 'css_warning') echo '<div class="icon-large" style="color:red;animation:pulse 0.5s infinite">🚨</div>';
                        
                        elseif (strpos($v, 'http') === 0) echo "<img src='$v' class='web-img'>";
                        elseif (strpos($v, 'icon_') === 0) {
                            $i='🌟';
                            if($v=='icon_earth') $i='🌍'; elseif($v=='icon_rock') $i='🪨'; elseif($v=='icon_life') $i='🧬';
                            elseif($v=='icon_water_drop') $i='💧'; elseif($v=='icon_dam') $i='🏗️'; elseif($v=='icon_surf') $i='🏄';
                            if($v=='icon_mountain') $i='🏔️'; 
                            elseif($v=='icon_cave') $i='🦇'; 
                            elseif($v=='icon_compass') $i='🧭'; 
                            elseif($v=='icon_badge') $i='👮';
                            elseif($v=='icon_telescope') $i='🔭';
                            elseif($v=='icon_railway') $i='🚂';
                            elseif($v=='icon_map_scatter') $i='🗺️';
                            elseif($v=='icon_backpack') $i='🎒';
                            elseif($v=='icon_tree_planting') $i='🌳';

                            if($v=='icon_tree') $i='🌳'; elseif($v=='icon_leaf_shiny') $i='🍃'; elseif($v=='icon_grass') $i='🌾'; 
                            elseif($v=='icon_park') $i='🏡'; elseif($v=='icon_forest_map') $i='🗺️';
                            elseif($v=='icon_soil_layers') $i='🥪'; elseif($v=='icon_soil_comp') $i='🧪'; elseif($v=='icon_shovel') $i='⛏️';
                            elseif($v=='icon_rock_plant') $i='🪨'; elseif($v=='icon_climate_soil') $i='🌦️'; elseif($v=='icon_protect_soil') $i='🛡️';
                            elseif($v=='icon_salt') $i='🧂';
                            
                            elseif($v=='icon_storm') $i='🌪️'; elseif($v=='icon_drought') $i='☀️'; elseif($v=='icon_china_map') $i='🇨🇳';
                            elseif($v=='icon_balance') $i='⚖️'; elseif($v=='icon_cold') $i='🥶'; elseif($v=='icon_arrow_map') $i='↘️';
                            elseif($v=='icon_sandstorm') $i='🏜️'; elseif($v=='icon_alert_red') $i='🛑'; elseif($v=='icon_earth_crack') $i='🏚️';
                            elseif($v=='icon_compare') $i='🆚'; elseif($v=='icon_chain') $i='🔗'; elseif($v=='icon_shield') $i='🛡️';
                            elseif($v=='icon_alert_yellow') $i='⚠️'; elseif($v=='icon_radar') $i='📡'; elseif($v=='icon_flood_safe') $i='🏊';
                            elseif($v=='icon_run_direction') $i='🏃'; elseif($v=='icon_rebuild') $i='🏗️'; elseif($v=='icon_kit') $i='⛑️';
                            elseif($v=='icon_drill') $i='📢'; elseif($v=='icon_money') $i='💰'; elseif($v=='icon_siren') $i='🚑';
                            elseif($v=='icon_satellite') $i='🛰️'; elseif($v=='icon_beidou') $i='🌌'; elseif($v=='icon_integration') $i='🧩';
                            elseif($v=='icon_phone') $i='📱'; elseif($v=='icon_key') $i='🔑'; elseif($v=='icon_drone') $i='🚁';
                            elseif($v=='icon_final') $i='🎓';
                            
                            echo '<div class="icon-large">'.$i.'</div>';
                        }
                        else echo '<div class="icon-large">🖼️</div>';
                    ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="nav-bar d-flex justify-content-between align-items-center">
        <button class="btn btn-secondary rounded-pill px-3 flex-grow-1 mx-1" onclick="move(-1)">上一页</button>
        <span class="mx-2 fw-bold text-muted small" id="pg-num" style="white-space:nowrap">1 / <?php echo count($slides); ?></span>
        <button class="btn btn-primary rounded-pill px-3 flex-grow-1 mx-1 text-truncate" id="next-btn" onclick="move(1)">下一页</button>
    </div>
    <script>
        let cur = 0; const total = <?php echo count($slides); ?>;
        const currentId = '<?php echo $id; ?>';
        function show(idx) {
            document.querySelectorAll('.slide-card').forEach(el => el.classList.remove('active'));
            document.getElementById('slide-' + idx).classList.add('active');
            cur = idx; document.getElementById('pg-num').innerText = (cur + 1) + " / " + total;
            const btn = document.getElementById('next-btn');
            if(cur === total - 1) { 
                btn.innerText = "进入考核 📝"; btn.classList.replace('btn-primary', 'btn-success'); 
                btn.onclick = () => window.location.href = 'quiz.php?id=' + currentId; 
            } else { 
                btn.innerText = "下一页"; btn.classList.replace('btn-success', 'btn-primary'); 
                btn.onclick = () => move(1); 
            }
        }
        function move(dir) { if(cur + dir >= 0 && cur + dir < total) show(cur + dir); }
        window.onload = function() { show(0); };
    </script>
</body>
</html>