import ProductView from "@/components/ProductView";
import type { Product } from '@/data/products';

// Simulated product data (Replace this with an API call)
const products: Product[] = [
    {
        id: "1",
        slug: "natural-glow-serum",
        name: "Natural Glow Serum",
        price: 49.99,
        rating: 4.8,
        review: 120,
        description: "A premium serum for glowing skin.",
        longDescription: "Our Natural Glow Serum is infused with Vitamin C and Hyaluronic Acid to give you a radiant complexion.",
        benefits: ["Hydrates skin", "Reduces dark spots", "Enhances glow"],
        specifications: { Volume: "30ml", "Skin Type": "All" },
        ingredients: "Vitamin C, Hyaluronic Acid, Aloe Vera",
        images: [
            "https://example.com/image1.jpg",
            "https://example.com/image2.jpg",
        ],
        category: "Skincare",
        breadcrumbs: ["Home", "Skincare", "Serums"],
        metaDescription: "Get glowing skin with our Natural Glow Serum.",
        reviews: [
            { id: "r1", user: "Jane Doe", rating: 5, comment: "Amazing product!" },
        ],
    },
];

export default function ProductPage({ params }: { params: { slug: string } }) {
    // Find product by slug
    const product = products.find((p) => p.slug === params.slug);

    if (!product) return <p>Product not found</p>;

    return <ProductView product={product} />;
}
