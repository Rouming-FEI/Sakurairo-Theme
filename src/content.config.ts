import { defineCollection, z } from "astro:content";

const posts = defineCollection({
    schema: z.object({
        title: z.string(),
        description: z.string().optional(),
        publishDate: z.coerce.date(),
        updatedDate: z.coerce.date().optional(),
        draft: z.boolean().default(false),
        tags: z.array(z.string()).default([]),
        categories: z.array(z.string()).default([]),
        cover: z.string().optional(),
        author: z.string().default("Rouming")
    })
});

export const collections = { posts };
