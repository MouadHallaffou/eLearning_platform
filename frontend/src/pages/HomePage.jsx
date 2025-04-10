import { Link } from "react-router-dom";

const HomePage = () => {
  return (
    <div className="text-center py-12">
      <h1 className="text-4xl font-bold mb-6">Bienvenue sur E-Learning</h1>
      <p className="text-xl mb-8">Découvrez nos cours et catégories</p>
      
      <div className="flex justify-center gap-4">
        <Link 
          to="/courses" 
          className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
          Voir les cours
        </Link>
        <Link 
          to="/categories" 
          className="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300"
        >
          Explorer les catégories
        </Link>
      </div>
    </div>
  );
};

export default HomePage;