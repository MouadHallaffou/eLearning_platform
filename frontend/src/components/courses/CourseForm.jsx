import React, { useState, useEffect } from 'react';
import { course } from '../../services/api';
import { useNavigate, useParams } from 'react-router-dom';
// import Loader from '../common/Loader';
// import ErrorMessage from '../common/ErrorMessage';

function CourseForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [categories, setCategories] = useState([]);
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    content: '',
    duration: '',
    level: '',
    price: '',
    cover: null,
    category_id: '',
    tags: []
  });

  // Charger les données existantes pour l'édition
  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        
        // Charger les catégories
        const categoriesResponse = await course.getCategories();
        setCategories(categoriesResponse.data.data);
        
        // Si c'est une édition, charger le cours existant
        if (id) {
          const courseResponse = await course.getCourse(id);
          const courseData = courseResponse.data.data;
          setFormData({
            ...courseData,
            tags: courseData.tags ? courseData.tags.split(', ') : []
          });
        }
      } catch (err) {
        setError(err.response?.data?.message || "Erreur lors du chargement des données");
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [id]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleFileChange = (e) => {
    setFormData(prev => ({
      ...prev,
      cover: e.target.files[0]
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const formPayload = new FormData();
      Object.keys(formData).forEach(key => {
        if (key === 'tags') {
          formPayload.append(key, formData[key].join(', '));
        } else if (formData[key] !== null) {
          formPayload.append(key, formData[key]);
        }
      });

      if (id) {
        await course.updateCourse(id, formPayload);
      } else {
        await course.createCourse(formPayload);
      }
      
      navigate('/courses');
    } catch (err) {
      setError(err.response?.data?.message || "Une erreur est survenue");
    } finally {
      setLoading(false);
    }
  };

  const isValid = () => {
    return formData.title && formData.description && formData.category_id;
  };

  if (loading && !formData.title) return <Loader />;
  // if (error) return <ErrorMessage message={error} onRetry={() => window.location.reload()} />;

  return (
    <div className="max-w-3xl mx-auto">
      <div className="bg-white shadow rounded-lg p-5 mb-5">
        <h2 className="text-2xl font-semibold mb-4">
          {id ? "Modifier le Cours" : "Créer un Nouveau Cours"}
        </h2>
        
        <form onSubmit={handleSubmit}>
          {/* Champ Titre */}
          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="title">
              Titre *
            </label>
            <input
              className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              id="title"
              type="text"
              name="title"
              value={formData.title}
              onChange={handleChange}
              required
            />
          </div>

          {/* Champ Description */}
          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="description">
              Description *
            </label>
            <textarea
              className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline min-h-[120px]"
              id="description"
              name="description"
              value={formData.description}
              onChange={handleChange}
              required
            />
          </div>

          {/* Champ Contenu */}
          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="content">
              Contenu détaillé
            </label>
            <textarea
              className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline min-h-[200px]"
              id="content"
              name="content"
              value={formData.content}
              onChange={handleChange}
            />
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            {/* Champ Durée */}
            <div>
              <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="duration">
                Durée (heures)
              </label>
              <input
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="duration"
                type="number"
                min="0"
                name="duration"
                value={formData.duration}
                onChange={handleChange}
              />
            </div>

            {/* Champ Niveau */}
            <div>
              <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="level">
                Niveau
              </label>
              <select
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="level"
                name="level"
                value={formData.level}
                onChange={handleChange}
              >
                <option value="">Sélectionner</option>
                <option value="débutant">Débutant</option>
                <option value="intermédiaire">Intermédiaire</option>
                <option value="avancé">Avancé</option>
              </select>
            </div>

            {/* Champ Prix */}
            <div>
              <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="price">
                Prix (€)
              </label>
              <input
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="price"
                type="number"
                step="0.01"
                min="0"
                name="price"
                value={formData.price}
                onChange={handleChange}
              />
            </div>
          </div>

          {/* Champ Image de couverture */}
          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="cover">
              Image de couverture
            </label>
            <input
              type="file"
              id="cover"
              name="cover"
              accept="image/*"
              onChange={handleFileChange}
              className="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100"
            />
            {formData.cover instanceof File ? (
              <p className="mt-1 text-sm text-gray-500">Fichier sélectionné: {formData.cover.name}</p>
            ) : formData.cover ? (
              <div className="mt-2">
                <p className="text-sm text-gray-500">Image actuelle:</p>
                <img 
                  src={formData.cover} 
                  alt="Couverture actuelle" 
                  className="mt-1 h-20 object-cover rounded"
                />
              </div>
            ) : null}
          </div>

          {/* Champ Catégorie */}
          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="category_id">
              Catégorie *
            </label>
            <select
              className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              id="category_id"
              name="category_id"
              value={formData.category_id}
              onChange={handleChange}
              required
            >
              <option value="">Sélectionner une catégorie</option>
              {categories.map((category) => (
                <option key={category.id} value={category.id}>
                  {category.name}
                </option>
              ))}
            </select>
          </div>

          {/* Champ Tags */}
          <div className="mb-6">
            <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="tags">
              Tags
            </label>
            <input
              className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              id="tags"
              type="text"
              name="tags"
              placeholder="Séparer les tags par des virgules"
              value={formData.tags.join(", ")}
              onChange={(e) =>
                setFormData({
                  ...formData,
                  tags: e.target.value.split(",").map(tag => tag.trim()).filter(tag => tag)
                })
              }
            />
            <p className="text-xs text-gray-500 mt-1">Exemple: react, javascript, frontend</p>
          </div>

          {/* Boutons */}
          <div className="flex justify-between items-center mt-6">
            <button
              type="button"
              onClick={() => navigate('/courses')}
              className="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
            >
              Annuler
            </button>
            <button
              type="submit"
              disabled={loading || !isValid()}
              className={`px-4 py-2 rounded-md text-white ${loading || !isValid() ? 'bg-blue-300' : 'bg-blue-600 hover:bg-blue-700'}`}
            >
              {loading ? (
                <span className="flex items-center">
                  <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Enregistrement...
                </span>
              ) : id ? "Mettre à jour" : "Créer le cours"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

export default CourseForm;