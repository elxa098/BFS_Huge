# Screencast-Skript: TicTacToe-Funktion

## Einleitung

- Begrüßung: „Hallo, ich zeige Ihnen jetzt meine TicTacToe-Funktion in der Anwendung.“
- Ziel: „Ich präsentiere zuerst die Benutzeransicht im Browser und anschließend die zentrale Spiellogik im Code.“
- Hinweis: „Die Aufnahme wird ungefähr fünf Minuten dauern."

---

## Teil 1: Allgemeine Funktionalität

### 1. Anmeldung und Navigation

- Zeige die Startseite der Anwendung.
- Melde dich als Benutzer*in an.
- Erkläre: „Die TicTacToe-Funktion ist nur für angemeldete Benutzerinnen und Benutzer verfügbar."
- Navigiere im Menü zu `TicTacToe`.
- Sage: „Hier öffnet sich das TicTacToe-Spiel, das zwei eingeloggte Spielende gegeneinander spielen lässt."

### 2. Gegner auswählen

- Zeige das Dropdown „Gegner auswählen“.
- Wähle einen anderen Benutzer aus der Liste.
- Erkläre:
  - „Ich wähle einen Gegner aus, mit dem ich ein Spiel starten möchte."
  - „Wenn bereits ein Spiel zwischen diesen beiden Benutzern existiert, wird es geladen."

### 3. Spielbrett und Statusanzeige

- Zeige das 3x3-Spielbrett.
- Lies den Status vor: zum Beispiel `Du bist dran!` oder `Gegner ist dran!`.
- Erkläre:
  - „Ich sehe hier das aktuelle Spielbrett."
  - „Ein Feld wird deaktiviert, wenn es schon besetzt ist oder wenn ich nicht an der Reihe bin."

### 4. Spielzug demonstrieren

- Führe mindestens einen Zug aus:
  - Klicke ein freies Feld für `X`.
  - Beschreibe, dass dadurch ein POST-Request an den Server gesendet wird.
- Erkläre:
  - „Jeder Zug wird direkt in der Datenbank gespeichert."
  - „Das System prüft vor dem Speichern, ob das Feld frei ist und ob der Spieler gerade am Zug ist."

### 5. Spielstand und Ergebnis

- Zeige, wie das System einen Gewinner oder ein Unentschieden anzeigt.
- Erkläre:
  - „Wenn drei gleiche Symbole in einer Reihe, Spalte oder Diagonale liegen, ist das Spiel gewonnen."
  - „Wenn alle neun Felder belegt sind und niemand gewonnen hat, endet das Spiel als Unentschieden."

### 6. Spiel zurücksetzen

- Klicke auf `Spiel zurücksetzen`.
- Erkläre:
  - „Diese Schaltfläche löscht das aktuelle Spiel aus der Datenbank."
  - „Danach kann ein neues Spiel gestartet werden."

---

## Teil 2: Spezielle Funktionen und Code

### 1. Wichtige Dateien öffnen

- Öffne `application/controller/TicTacToeController.php`.
- Öffne `application/model/TicTacToeModel.php`.
- Erkläre:
  - „Der Controller steuert die Spielabläufe und den Status für die Ansicht."
  - „Das Model kümmert sich um die Datenbankoperationen."

### 2. Spiel starten und laden

- Zeige im Controller die Funktion `getOrCreateGame()`.
- Sage:
  - „Hier wird geprüft, ob ein Spiel zwischen zwei Spielern bereits existiert."
  - „Wenn nicht, wird ein neues Spiel mit `createNewGame()` angelegt."

### 3. Zugreihenfolge ermitteln

- Zeige `TicTacToeModel::getCurrentTurn()`.
- Erkläre:
  - „Die Funktion zählt die bisherigen Züge."
  - „Sind es gerade Züge, ist `player_x` am Zug, bei ungerader Anzahl `player_o`."

### 4. Spielbrett aus der Datenbank laden

- Zeige `TicTacToeModel::getBoard()`.
- Erkläre:
  - „Alle gespeicherten Spielzüge werden geladen und in ein Brett mit `X` und `O` umgewandelt."
  - „So kann das Spiel jederzeit wieder angezeigt werden."

### 5. Spielzug speichern

- Zeige `playGame()` im Controller und `makeMove()` im Model.
- Erkläre:
  - „Der Controller prüft das Feld und ob ein Spiel aktiv ist."
  - „Dann wird der Zug in `tictactoe_moves` gespeichert."

### 6. Gewinnerprüfung

- Zeige die Konstante `WINNING_LINES` und die Methode `checkForWinner()`.
- Erkläre:
  - „Alle möglichen Siegkombinationen werden geprüft."
  - „Bei drei gleichen Symbolen in einer Gewinnlinie wird das Spiel beendet."

### 7. Spiel beenden und speichern

- Zeige `TicTacToeModel::finishGame()`.
- Erkläre:
  - „Der Spielstatus wird auf `finished` gesetzt."
  - „Das Gewinnerfeld `winner_id` wird gespeichert, bei Unentschieden wird `-1` verwendet."

### 8. Datenbankstruktur kurz erläutern

- Beschreibe die Tabellen:
  - `tictactoe_games`: `id`, `creator_id`, `player_x_id`, `player_o_id`, `status`, `winner_id`, `created_at`, `finished_at`
  - `tictactoe_moves`: `game_id`, `user_id`, `position`, `created_at`
- Erkläre:
  - „So werden Spielverlauf und Ergebnis dauerhaft gespeichert."

---

## Konkreter Sprechertext

### Einleitung

„Hallo, ich zeige Ihnen jetzt meine TicTacToe-Funktion in der Anwendung. Ich präsentiere zuerst die Benutzeransicht im Browser und anschließend die zentrale Spiellogik im Code. Die Aufnahme dauert ungefähr fünf Minuten."

### Teil 1: Allgemeine Funktionalität

„Ich bin jetzt in der Anwendung angemeldet und navigiere zum TicTacToe-Spiel. Die TicTacToe-Funktion ist nur für angemeldete Benutzerinnen und Benutzer verfügbar."

„Hier sehe ich das TicTacToe-Spielfeld und die Möglichkeit, einen Gegner auszuwählen. Ich wähle einen Gegner aus der Liste, um ein Spiel zu starten oder ein vorhandenes Spiel zu laden."

„Der Status zeigt an, wer gerade am Zug ist: `Du bist dran!` oder `Gegner ist dran!`. Das Spielbrett besteht aus neun Feldern. Wenn ein Feld bereits belegt ist, wird der Button deaktiviert."

„Ich führe jetzt einen Zug aus, indem ich ein freies Feld auswähle. Dieser Klick sendet das Feld als Zug an den Server. Jeder Zug wird direkt in der Datenbank gespeichert. Vor dem Speichern prüft das System, ob das Feld noch frei ist und ob der Spieler gerade am Zug ist."

„Wenn drei gleiche Symbole in einer Reihe, Spalte oder Diagonale liegen, ist das Spiel gewonnen. Wenn alle neun Felder belegt sind und niemand gewonnen hat, endet das Spiel als Unentschieden."

„Ich klicke jetzt auf ‚Spiel zurücksetzen‘. Diese Schaltfläche löscht das aktuelle Spiel aus der Datenbank, und danach kann ein neues Spiel gestartet werden."

### Teil 2: Spezielle Funktionen und Code

„Ich öffne jetzt den Code, um die technische Umsetzung zu erläutern. Wichtig sind die Dateien `application/controller/TicTacToeController.php` und `application/model/TicTacToeModel.php`. Der Controller steuert die Spielabläufe und den Status für die Ansicht. Das Model kümmert sich um die Datenbankoperationen."

„Zuerst prüft die Funktion `getOrCreateGame()`, ob ein Spiel zwischen den beiden Spielern bereits existiert. Wenn nicht, wird ein neues Spiel mit `createNewGame()` angelegt."

„Die Funktion `getCurrentTurn()` ermittelt die Zugreihenfolge, indem sie die bisherigen Züge zählt. Sind es gerade Züge, ist `player_x` am Zug, bei ungerader Anzahl ist `player_o` dran."

„Mit `getBoard()` werden alle gespeicherten Spielzüge aus der Datenbank geladen und in ein Brett mit `X` und `O` umgewandelt. So kann das Spiel jederzeit wieder angezeigt werden."

„Wenn ein Spieler einen Zug macht, prüft `playGame()` im Controller das Feld und ob ein Spiel aktiv ist, und `makeMove()` im Model speichert den Zug in `tictactoe_moves`."

„Die Gewinnerprüfung passiert in `checkForWinner()`. Dort werden alle möglichen Siegkombinationen geprüft. Bei drei gleichen Symbolen in einer Gewinnlinie wird das Spiel beendet."

„In `finishGame()` wird der Spielstatus auf `finished` gesetzt. Das Gewinnerfeld `winner_id` wird gespeichert, bei einem Unentschieden wird `-1` verwendet."

„Die Datenbank speichert die Spieldaten in zwei Tabellen: `tictactoe_games` für Spielmetadaten und `tictactoe_moves` für einzelne Züge. So bleiben Spielverlauf und Ergebnis dauerhaft erhalten."

### Abschluss

„Ich habe gezeigt, wie das TicTacToe-Spiel gestartet, gespielt und zurückgesetzt wird. Ich habe außerdem die zentrale Logik erklärt: Zugverwaltung, Brett-Ladung, Gewinnerprüfung und Speicherung."

---

## Zeitplan für 5 Minuten

1. Einleitung: 20 Sekunden
2. Benutzeransicht im Browser: 2 Minuten
3. Code-Erklärung: 2,5 Minuten
4. Abschluss: 30 Sekunden

> Hinweis: Achte darauf, dass Stimme und Bildschirmaufnahme gut verständlich sind und dass du bei jedem Schritt kurz erklärst, was gerade passiert und warum es wichtig ist.