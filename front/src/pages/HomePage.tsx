export default function HomePage() {
    return (
        <section className="grid grid-cols-1 lg:grid-cols-[1.618fr_1fr] gap-8">
            <div className="card bg-base-100 shadow-xl p-6">
                <h3 className="text-xl font-semibold mb-4">Statistiques générales</h3>

                <div className="stats shadow w-full">
                    <div className="stat">
                        <div className="stat-title">Utilisateurs</div>
                        <div className="stat-value text-primary">1,248</div>
                        <div className="stat-desc">↗︎ 12% ce mois</div>
                    </div>

                    <div className="stat">
                        <div className="stat-title">Ventes</div>
                        <div className="stat-value text-secondary">89</div>
                        <div className="stat-desc">↘︎ 3% cette semaine</div>
                    </div>

                    <div className="stat">
                        <div className="stat-title">Satisfaction</div>
                        <div className="stat-value text-accent">95%</div>
                        <div className="stat-desc">↗︎ +5%</div>
                    </div>
                </div>
            </div>

            <div className="card bg-base-100 shadow-xl p-6">
                <h3 className="text-xl font-semibold mb-4">Activité récente</h3>
                <ul className="menu bg-base-100 rounded-box">
                    <li><a>✅ Nouveau produit ajouté</a></li>
                    <li><a>🧑‍💻 Nouvel utilisateur inscrit</a></li>
                    <li><a>💬 3 nouveaux messages</a></li>
                    <li><a>📦 Commande #152 expédiée</a></li>
                </ul>
            </div>
        </section>
    );
}
