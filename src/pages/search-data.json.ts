import type { APIRoute } from "astro";
import { getPublishedPosts } from "../lib/posts";

export const GET: APIRoute = async () => {
    const posts = await getPublishedPosts();

    const payload = posts.map((post) => ({
        title: post.data.title,
        description: post.data.description ?? "",
        tags: post.data.tags,
        url: `/posts/${post.slug}`
    }));

    return new Response(JSON.stringify(payload), {
        headers: {
            "Content-Type": "application/json; charset=utf-8"
        }
    });
};
