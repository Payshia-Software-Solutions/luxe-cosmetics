import Shop from "@/components/shop/Shop";
import React from "react";
import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Shop | Premium Beauty & Skincare Products Online",
  description:
    "Discover a wide range of premium beauty and skincare products at our Cosmetic Shop. Shop for makeup, skincare, haircare, and more with fast delivery and expert advice.",
  keywords:
    "cosmetic shop, beauty products, skincare, makeup, skincare products, premium cosmetics, online beauty store, skincare online, makeup online, beauty essentials",
  robots: "index, follow",
  viewport: "width=device-width, initial-scale=1",
};

const Page: React.FC = () => {
  return (
    <div className="">
      <Shop />
    </div>
  );
};

export default Page;
