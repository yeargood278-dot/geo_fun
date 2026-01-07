<?php include 'data_zoo.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>地理乐 Geo_Fun </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .hero { background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%); color: white; padding: 100px 0; border-radius: 0 0 50% 50% / 30px; margin-bottom: 50px; }
        .book-card { border: none; border-radius: 15px; background: white; transition: 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .book-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .bar { height: 8px; width: 100%; }
    </style>
</head>
<body>
    <div class="hero text-center">
        <h1 class="display-4 fw-bold">🌏 地理乐 Geo_Fun </h1>
        <p class="lead mt-3">疯狂动物城 · 警校地理培训基地</p>
    </div>
    <div class="container mb-5">
        <div class="row g-4 justify-content-center">
            <?php foreach ($books as $id => $book): ?>
            <div class="col-md-4 col-sm-6">
                <div class="card book-card h-100">
                    <div class="bar" style="background: <?php echo $book['color']; ?>"></div>
                    <div class="card-body text-center p-4">
                        <div style="font-size: 3rem; margin: 20px 0;"><?php echo $book['icon']; ?></div>
                        <h4 class="fw-bold"><?php echo $book['title']; ?></h4>
                        <p class="text-muted small"><?php echo $book['desc']; ?></p>
                        <?php if($book['status'] == 'active'): ?>
                            <a href="chapter_map.php?bid=<?php echo $id; ?>" class="btn btn-primary rounded-pill w-100 mt-3" style="background: <?php echo $book['color']; ?>; border:none;">开始学习</a>
                        <?php else: ?>
                            <button class="btn btn-light rounded-pill w-100 mt-3 text-muted" disabled>待解锁</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>