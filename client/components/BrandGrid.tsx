
import Image from "next/image";

const brands = [
    { name: "L'Oréal", logo: "https://1000logos.net/wp-content/uploads/2022/04/LOreal-logo.png" },
    { name: "Maybelline", logo: "https://1000logos.net/wp-content/uploads/2021/04/Maybelline-logo.png" },
    { name: "MAC", logo: "https://paseosanpedro.com/wp-content/uploads/2023/06/mac.jpg" },
    { name: "Chanel", logo: "https://upload.wikimedia.org/wikipedia/en/thumb/9/92/Chanel_logo_interlocking_cs.svg/1200px-Chanel_logo_interlocking_cs.svg.png" },
    { name: "Dior", logo: "https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/c8362e49047677.58a9ce2eb32f4.jpg" },
    { name: "Sephora", logo: "https://1000logos.net/wp-content/uploads/2018/08/Sephora-Emblem.png" },
    { name: "Clinique", logo: "https://1000logos.net/wp-content/uploads/2020/04/Clinique-Logo.png" },
    { name: "Estée Lauder", logo: "https://1000logos.net/wp-content/uploads/2020/05/Logo-Estee-Lauder.jpg" },
];


export default function BrandGrid() {

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mb-5">
            <h2 className="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-8">
                Our Cosmetic Brands
            </h2>
            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                {brands.map((brand, index) => (
                    <div
                        key={index}
                        className="p-4 border rounded-lg shadow-md bg-white dark:bg-[#fff0e9] flex flex-col items-center"
                    >
                        <Image src={brand.logo} alt={brand.name} className="w-20 h-20 object-contain" width={100} height={100} />
                        <p className="mt-2 text-sm font-semibold text-gray-900 dark:text-black">{brand.name}</p>
                    </div>
                ))}
            </div>
        </div>
    );
}
