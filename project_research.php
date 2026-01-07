<?php include 'data_zoo.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>问题研究 - 综合分析能力提升</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', 'Microsoft YaHei', sans-serif; padding-bottom: 60px; }
        .header-section { background: linear-gradient(135deg, #8e44ad 0%, #3498db 100%); color: white; padding: 60px 20px; text-align: center; border-radius: 0 0 30px 30px; margin-bottom: 40px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .project-card { border: none; border-radius: 15px; margin-bottom: 15px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .project-card:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
        .accordion-button { background-color: white; color: #2c3e50; font-weight: bold; font-size: 1.1rem; padding: 20px; border: none; }
        .accordion-button:not(.collapsed) { background-color: #f8f9fa; color: #8e44ad; box-shadow: inset 0 -1px 0 rgba(0,0,0,.125); }
        .accordion-button:focus { box-shadow: none; border-color: rgba(142,68,173,0.1); }
        .icon-box { font-size: 1.5rem; margin-right: 15px; width: 40px; text-align: center; }
        .content-box { background: #fff; padding: 25px; border-top: 3px solid #8e44ad; }
        .label-tag { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; margin-bottom: 10px; }
        .tag-point { background: #e8f6f3; color: #1abc9c; }
        .tag-value { background: #fef5e7; color: #f39c12; }
    </style>
</head>
<body>

    <div class="header-section animate__animated animate__fadeInDown">
        <h1 class="display-5 fw-bold mb-3">🚀 问题研究：综合分析能力提升</h1>
        <p class="lead opacity-75">打破章节壁垒 · 融合全书知识 · 解决现实问题</p>
    </div>

    <div class="container" style="max-width: 900px;">
        <div class="accordion" id="projectAccordion">
            <?php foreach($research_projects as $index => $proj): ?>
            <div class="accordion-item project-card animate__animated animate__fadeInUp" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="false" aria-controls="collapse<?php echo $index; ?>">
                        <span class="icon-box"><?php echo $proj['icon']; ?></span>
                        <?php echo $proj['title']; ?>
                    </button>
                </h2>
                <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#projectAccordion">
                    <div class="accordion-body content-box">
                        <div class="mb-4">
                            <span class="label-tag tag-point">💡 主要观点与研究思路</span>
                            <p class="text-secondary lh-lg mb-0"><?php echo $proj['points']; ?></p>
                        </div>
                        <div>
                            <span class="label-tag tag-value">📈 研究趋势与价值</span>
                            <p class="text-secondary lh-lg mb-0"><?php echo $proj['value']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5 mb-5 text-muted">
            <small>✨ 点击标题展开详情，再次点击或点击其他项目自动收起 ✨</small>
        </div>

        <div class="text-center mb-5 pb-5">
            <a href="index.php" class="btn btn-primary rounded-pill px-4 mx-2 shadow">🏠 首页</a>
            <a href="chapter_map.php" class="btn btn-secondary rounded-pill px-4 mx-2 shadow">🗺️ 地图</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>