<?php

namespace App\Http\Controllers\API;

use App\Models\ChangeLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ChangeLogController extends Controller
{
  public function restoreEntity($id)
  {
    // start transaction
    DB::beginTransaction(); // если внутри будет ошибка то откат
    try {

      // Найти запись в ChangeLog по ID
      $changeLog = ChangeLog::find($id);


      // Проверка есть ли запись
      if (!$changeLog) {
        return response()->json(['message' => 'entity not found'], 404);
      }

      // автоматом преобразуем имя таблицы в имя модели и получаем полное имя класса (в единственном числе названия) - собтрается полный путь до класса модели
      $modelClass = 'App\\Models\\' . ucfirst(\Illuminate\Support\Str::singular($changeLog->entity_type));
    
    
      // Проверка объявлен ли класс
      if (!class_exists($modelClass)) {
        return response()->json(['message' => 'model not found'], 404);
      }

   // Находим запись по ID сущности
      $entity = $modelClass::find($changeLog->entity_id);

      if (!$entity) {
        return response()->json(['message' => 'entity not found'], 404);
      }

      // восстановим состояния из  before
      // Декодируем before в ассоциативный массив
      $beforeState = json_decode($changeLog->before, true);
      // Заполняем свойства сущности данными из массива
      $entity->fill($beforeState);
      // Сохраняем изменения в бд
      $entity->save();

      // Фиксируем транзакцию
      DB::commit();

      return response()->json([
        'message' => 'entity restored success',
        'restored_entity' => $entity,
      ]);
    } catch (\Exception $e) {

      // Откатываем изменения при ошибке
      DB::rollBack();
      return response()->json(['message' => 'fail  restore entity', 'error' => $e->getMessage()], 500);
    }
  }
}
