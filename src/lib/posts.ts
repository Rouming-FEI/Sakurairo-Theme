import { getCollection, type CollectionEntry } from "astro:content";

export type PostEntry = CollectionEntry<"posts">;

export async function getPublishedPosts(): Promise<PostEntry[]> {
    const posts = await getCollection("posts", ({ data }: PostEntry) => !data.draft);
    return posts.sort((a: PostEntry, b: PostEntry) => b.data.publishDate.valueOf() - a.data.publishDate.valueOf());
}

export function getTagMap(posts: PostEntry[]): Map<string, PostEntry[]> {
    const map = new Map<string, PostEntry[]>();

    for (const post of posts) {
        for (const rawTag of post.data.tags) {
            const tag = rawTag.trim();
            if (!tag) continue;
            const list = map.get(tag) ?? [];
            list.push(post);
            map.set(tag, list);
        }
    }

    return map;
}

export function slugifyTag(tag: string): string {
    return encodeURIComponent(tag.toLowerCase().replace(/\s+/g, "-").replace(/[^\w\u4e00-\u9fa5-]/g, ""));
}

export function getCategoryMap(posts: PostEntry[]): Map<string, PostEntry[]> {
    const map = new Map<string, PostEntry[]>();

    for (const post of posts) {
        for (const rawCategory of post.data.categories ?? []) {
            const category = rawCategory.trim();
            if (!category) continue;
            const list = map.get(category) ?? [];
            list.push(post);
            map.set(category, list);
        }
    }

    return map;
}

export function getAuthorMap(posts: PostEntry[]): Map<string, PostEntry[]> {
    const map = new Map<string, PostEntry[]>();

    for (const post of posts) {
        const author = (post.data.author || "").trim();
        if (!author) continue;
        const list = map.get(author) ?? [];
        list.push(post);
        map.set(author, list);
    }

    return map;
}

export function slugifyTerm(value: string): string {
    return encodeURIComponent(value.toLowerCase().replace(/\s+/g, "-").replace(/[^\w\u4e00-\u9fa5-]/g, ""));
}

export function groupPostsByYear(posts: PostEntry[]): Map<string, PostEntry[]> {
    const map = new Map<string, PostEntry[]>();

    for (const post of posts) {
        const year = String(post.data.publishDate.getFullYear());
        const list = map.get(year) ?? [];
        list.push(post);
        map.set(year, list);
    }

    return map;
}

export function paginate<T>(items: T[], page: number, pageSize: number) {
    const total = Math.max(1, Math.ceil(items.length / pageSize));
    const current = Math.min(Math.max(page, 1), total);
    const start = (current - 1) * pageSize;

    return {
        total,
        current,
        items: items.slice(start, start + pageSize),
        hasPrev: current > 1,
        hasNext: current < total
    };
}
