<?php include 'data_zoo.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>警校作战地图</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
    <style>
        body { background: #eef2f3; height: 100vh; display: flex; flex-direction: column; overflow: hidden; }
        .navbar { background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); z-index: 10; padding: 10px 20px; }
        
        /* 地图视口：支持触摸滚动 */
        .map-viewport { 
            flex: 1; 
            width: 100%; 
            overflow: auto; /* 允许滚动 */
            -webkit-overflow-scrolling: touch; /* iOS流畅滚动 */
            display: flex; 
            justify-content: center; /* 居中显示 */
            align-items: flex-start; /* 顶部对齐，防止长图被截断 */
            padding: 20px; 
            background-color: #f8f9fa; 
            background-image: radial-gradient(#dee2e6 1px, transparent 1px); 
            background-size: 20px 20px; 
        }

        /* Mermaid 容器适配 */
        .mermaid {
            width: 100%;
            max-width: 1200px; /* 限制最大宽度，防止大屏过宽 */
            min-width: 300px; /* 保证手机端不被压缩太小 */
        }

        /* 节点样式增强 */
        g.node rect, g.node circle, g.node polygon {
            stroke-width: 2px !important; cursor: pointer !important; 
            transition: all 0.2s ease !important;
            filter: drop-shadow(3px 3px 0px rgba(0,0,0,0.1)) !important;
        }
        g.node:hover rect {
            transform: scale(1.05); filter: drop-shadow(5px 5px 2px rgba(0,0,0,0.2)) !important;
        }
        g.node:active rect {
            transform: scale(0.95);
        }
        /* 强制文字颜色 */
        g.node .label { color: white !important; font-family: 'Microsoft YaHei', sans-serif; }
    </style>
</head>
<body>
    <nav class="navbar d-flex justify-content-between">
        <span class="navbar-brand fw-bold text-primary" style="font-size: 1.1rem;">🗺️ 警校作战地图</span>
        <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">🏠 首页</a>
    </nav>
    <div class="map-viewport">
        <div class="mermaid">
            <?php echo $b1_mindmap; ?>
        </div>
    </div>
    <script>
        // 初始化 mermaid，自动适应宽度
        mermaid.initialize({ 
            startOnLoad: true, 
            theme: 'base', 
            securityLevel: 'loose', 
            flowchart: { 
                useMaxWidth: true, // 允许缩放以适应屏幕
                htmlLabels: true, 
                curve: 'basis' 
            } 
        });
    </script>
</body>
</html>