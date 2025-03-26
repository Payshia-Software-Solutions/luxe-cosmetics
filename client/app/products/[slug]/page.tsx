import ProductView from "@/components/ProductView";
import { products } from '@/data/products';
import { Metadata } from 'next';

export const metadata: Metadata = {
    title: "Cosmetic Shop | Premium Beauty & Skincare Products Online",
    description: "Discover a wide range of premium beauty and skincare products at our Cosmetic Shop. Shop for makeup, skincare, haircare, and more with fast delivery and expert advice.",
    keywords: "cosmetic shop, beauty products, skincare, makeup, skincare products, premium cosmetics, online beauty store, skincare online, makeup online, beauty essentials",
    robots: "index, follow",
    viewport: "width=device-width, initial-scale=1",
};



export default async function Page({
    params,
}: {
    params: Promise<{ slug: string }>
}) {
    const { slug } = await params
    // Find product by slug
    const product = products.find((p) => p.slug === slug);
    if (!product) {
        return (
            <div className="text-center py-8">
                <h1 className="text-3xl font-bold">Product Not Found</h1>
                <p>The product you&apos;re looking for is not available.</p>
            </div>
        );
    }

    // Return the ProductView component with the found product
    return <ProductView product={product} />;
}
