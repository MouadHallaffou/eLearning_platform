import React, { useEffect, useState } from "react";
import { category } from "../../services/api";

const CategoryList = () => {
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    const fetchCategoryData = async () => {
      try {
        const response = await category.getCategories();
        setCategories(response.data.data);
      } catch (error) {
        console.error("Error fetching categories:", error);
      }
    };
    
    fetchCategoryData();
  }, []);

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-6 text-gray-800">Liste des Catégories</h1>
      
      <div className="overflow-x-auto">
        <table className="min-w-full bg-white rounded-lg overflow-hidden">
          <thead className="bg-gray-100">
            <tr>
              <th className="py-3 px-4 text-left text-gray-700 font-semibold">Catégorie Principale</th>
              <th className="py-3 px-4 text-left text-gray-700 font-semibold">Sous-catégories</th>
              <th className="py-3 px-4 text-left text-gray-700 font-semibold">Date de création</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            {categories.map((ctg, index) => (
              <tr key={index} className={index % 2 === 0 ? "bg-gray-50" : "bg-white"}>
                <td className="py-4 px-4 font-medium text-gray-900">
                  {ctg.name}
                </td>
                <td className="py-4 px-4">
                  <ul className="space-y-1">
                    {ctg.subcategories.map((sub, subIndex) => (
                      <li key={subIndex} className="text-gray-600">
                        • {sub.name}
                      </li>
                    ))}
                    {ctg.subcategories.length === 0 && (
                      <li className="text-gray-400 italic">Aucune sous-catégorie</li>
                    )}
                  </ul>
                </td>
                <td className="py-4 px-4 text-gray-500">
                  {new Date(ctg.created_at).toLocaleDateString()}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
        
        {categories.length === 0 && (
          <div className="text-center py-8 text-gray-500">
            Aucune catégorie disponible
          </div>
        )}
      </div>
    </div>
  );
};

export default CategoryList;