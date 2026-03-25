export const siteConfig = {
    title: "Sakurairo Astro",
    description: "A static Astro theme refactored from the Sakurairo WordPress theme.",
    author: {
        name: "Rouming",
        avatar: "https://www.gravatar.com/avatar/?d=identicon&s=240",
        bio: "Love colorful and warm web interfaces, anime vibes, and clean reading experiences."
    },
    nav: [
        { href: "/", label: "首页" },
        { href: "/archive", label: "归档" },
        { href: "/categories", label: "分类" },
        { href: "/tags", label: "标签" },
        { href: "/authors", label: "作者" },
        { href: "/search", label: "搜索" },
        { href: "/about", label: "关于" }
    ],
    social: {
        github: "https://github.com/Rouming-FEI/Sakurairo-Theme"
    },
    comments: {
        enabled: false,
        repo: "owner/repo",
        repoId: "",
        category: "Announcements",
        categoryId: ""
    }
};
