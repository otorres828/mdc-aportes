    @Composable
    private fun SplashScreen() {
        Box(
            modifier = Modifier.fillMaxSize().background(Color(0xFFFFFCF7)),
            contentAlignment = Alignment.Center
        ) {
            Column(
                horizontalAlignment = Alignment.CenterHorizontally,
                verticalArrangement = Arrangement.spacedBy(18.dp),
                modifier = Modifier.padding(32.dp)
            ) {
                Box(
                    modifier = Modifier.size(88.dp).background(
                        Color(0xFF081E31),
                        androidx.compose.foundation.shape.CircleShape
                    ),
                    contentAlignment = Alignment.Center
                ) {
                    Text(text = "✝", fontSize = 48.sp, color = Color(0xFFD4A340))
                }
                Text(text = "Mansion de Cristo", fontSize = 24.sp, color = Color(0xFF081E31))
                Text(text = "IGLESIA & COMUNIDAD", fontSize = 11.sp, color = Color(0xFF75829B))
                Spacer(modifier = Modifier.height(20.dp))
                androidx.compose.material3.CircularProgressIndicator(
                    modifier = Modifier.size(36.dp),
                    color = Color(0xFFD4A340),
                    strokeWidth = 3.dp
                )
                Text(text = "Cargando aplicación…", fontSize = 16.sp, color = Color(0xFF081E31))
                Text(text = "Tu generosidad transforma vidas", fontSize = 13.sp, color = Color(0xFF75829B))
            }
        }
    }

