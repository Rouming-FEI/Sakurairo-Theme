---
title: 从 WordPress 主题迁移到 Astro
description: 主题重构策略与映射规则。
publishDate: 2026-03-25
tags:
  - WordPress
  - Astro
  - Sakurairo
categories:
  - 主题重构
---

## 模板映射

- header.php + footer.php -> 布局组件
- index.php -> src/pages/index.astro
- single.php -> src/pages/posts/[slug].astro
- user/template-about.php -> src/pages/about.astro

## 下一步

1. 把分类、标签、归档等页面继续映射到 Astro 路由。
2. 将评论改为第三方评论系统（如 Giscus）。
3. 按需引入主题里的交互脚本并做模块化拆分。
